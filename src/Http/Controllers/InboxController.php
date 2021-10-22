<?php

namespace Xoshbin\Inbox\Http\Controllers;

use Illuminate\Http\Request;
use Xoshbin\Inbox\Models\InboxEmail;
use Xoshbin\Inbox\Models\InboxReply;
use Xoshbin\Inbox\Models\Customer;
use Xoshbin\Inbox\Models\InboxTemplate;
use DB;
use Illuminate\Support\Facades\Mail;
use Xoshbin\Inbox\Mail\InboxMail;
use Xoshbin\Inbox\Http\Resources\Email as EmailResource;

class InboxController extends Controller
{

    /**
     * Send dashboard emails in json
     *
     * @return Illuminate\Http\Resources\Json\JsonResource
     */
    public function emails()
    {
        // dd('in');
        if (! function_exists('imap_open')) {
            echo "IMAP is not configured.";
            exit();
        } else {
            /* IMAP Connection code with GMAIL IMAP */
            $imap_conn = imap_open('{imap.gmail.com:993/imap/ssl}INBOX', config('inbox.services.gmail.username'), config('inbox.services.gmail.password')) or die('Cannot connect to Gmail: ' . imap_last_error());
            
            /* SET email subject filter criteria */
            $inbox = imap_search($imap_conn, config('inbox.services.gmail.fetch'));
            // $temp = imap_fetch_overview($imap_conn, $inbox[11], 0);

            if (! empty($inbox)) {
                foreach ($inbox as $email) {
                    // Get email header information
                    $overview = imap_fetch_overview($imap_conn, $email, 0);
                    $header = imap_headerinfo($imap_conn,$email);
                    // Get email body
                    $message = quoted_printable_decode(imap_fetchbody($imap_conn, $email, '2'));
                    // dd($header->from[0]->mailbox . "@" . $header->from[0]->host);
                    $date = date("d F, Y", strtotime($overview[0]->date));
                    $emailcheck = InboxEmail::where('email_uid',$overview[0]->uid)->exists();
                    if($emailcheck != true){
                        $InboxEmail = new InboxEmail();
                        $InboxEmail->to = $header->reply_to[0]->mailbox."@".$header->reply_to[0]->host;
                        $InboxEmail->from = $overview[0]->from;
                        $InboxEmail->subject = $overview[0]->subject;
                        $InboxEmail->body = $message;
                        $InboxEmail->email_uid = $overview[0]->uid;
                        $InboxEmail->save();
                    }
                
                }
            }
             // Close imap connection
            imap_close($imap_conn);

        }
        
        $emails =  InboxEmail::orderBy('created_at', 'desc')->paginate(10);
        // dd($emails);
        return EmailResource::collection($emails);
    }

    /**
     * Send an email in json
     *
     * @param number                   $id
     * 
     * @return Illuminate\Http\Resources\Json\JsonResource
     */
    public function email($id)
    {
        
        $email = InboxEmail::where('id', $id)->withTrashed()->get();
        // dd($email);
        $emailsReplies = InboxReply::where('email_id', $id)->orderBy('created_at', 'asc')->get();
        // dd($emailsReplies);
        InboxEmail::where('id', $id)->update(['read' => 1]);
        $emailShow = EmailResource::collection($email);
        $emailData['show'] = $emailShow;
        $emailData['replies'] = $emailsReplies;
        return $emailData;
    }

    /**
     * Send a new email
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return message
     */
    public function send(Request $request)
    {
        // dd($request->input('to'));
        $details = [
            'subject' => $request->input('subject'),
            'body' => $request->input('html')
        ];

        $mailbody = new InboxMail($details);
        try{
            $response = Mail::to($request->input('to'))->send($mailbody);
            $InboxEmail = new InboxEmail;
            $InboxEmail->to = $request->input('to');
            $InboxEmail->from = 'faisal.azez345@gmail.com';
            $InboxEmail->subject = $request->input('subject');
            $InboxEmail->body = $mailbody->render();
            $InboxEmail->sent = 1;
            $InboxEmail->save();
        }
        catch(\Exception $e){
            $msg['message'] = $e->getMessage();
            $msg['status'] = 'error';
            return $msg;
            // dd($msg);
        }
        
        // dd($response);
        // $InboxEmail = new InboxEmail;
        // $InboxEmail->to = $request->input('to');
        // $InboxEmail->from = 'faisal.azez345@gmail.com';
        // $InboxEmail->subject = $request->input('subject');
        // $InboxEmail->body = $mailbody->render();
        // $InboxEmail->sent = 1;
        // $InboxEmail->save();

        return ['message' => $InboxEmail];
    }

    /**
     * Send a reply
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return message
     */
    public function sendreply(Request $request)
    {
        // dd($request->input('html'));
        $email = InboxEmail::where('id', $request->input('id'))->first();
        // dd($request);
        $emailReply = new InboxReply;
        $emailReply->to = $email->to;
        $emailReply->from = 'faisal.azez345@gmail.com';
        $emailReply->body = $request->input('html');
        $emailReply->email_id = $email->id;
        $emailReply->sent = 1;
        $emailReply->save();

        $details = [
            'subject' => $email->subject,
            'body' => $request->input('html')
        ];


        Mail::to($email->from)->send(new InboxMail($details));

        return ['message' => "Reply sent"];
    }

    /**
     * Send the forward
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return message
     */
    public function sendforward(Request $request)
    {
        $email = InboxEmail::where('id', $request->input('id'))->first();

        $details = [
            'subject' => $email->subject,
            'body' => $request->input('html')
        ];

        Mail::to($request->input('to'))->send(new InboxMail($details));

        return ['message' => "Email forward sent"];
    }

    /**
     * Make an email unread
     *
     * @param number                   $id
     * 
     * @return message
     */
    public function unread($id)
    {
        try {
            $email = InboxEmail::where('id', $id)->withTrashed()->first();
            $email->restore();

            if ($email->read == 1) {
                $email->read = 0;
                $email->save();
                return ['message' => "Email unread"];
            } else {
                $email->read = 1;
                $email->save();
                return ['message' => "Email read"];
            }

            
        } catch (\Throwable $th) {
            return ['message' => $th];
        }
    }

    /**
     * Toggle star an email
     *
     * @param number                   $id
     * 
     * @return message
     */
    public function star($id)
    {
        try {
            $email = InboxEmail::where('id', $id)->first();

            if ($email->starred == 1) {
                $email->starred = 0;
                $email->save();
                return ['message' => "Email unstarred"];
            } else {
                $email->starred = 1;
                $email->save();
                return ['message' => "Email starred"];
            }

            
        } catch (\Throwable $th) {
            return ['message' => $th];
        }
    }

    /**
     * Toggle important an email
     *
     * @param number                   $id
     * 
     * @return message
     */
    public function bookmark($id)
    {
        try {
            $email = InboxEmail::where('id', $id)->first();

            if ($email->bookmarked == 1) {
                $email->bookmarked = 0;
                $email->save();
                return ['message' => "Email removed from bookmark list"];
            } else {
                $email->bookmarked = 1;
                $email->save();
                return ['message' => "Email bookmarked"];
            }

            
        } catch (\Throwable $th) {
            return ['message' => $th];
        }
    }

    /**
     * Show starred emails.
     *
     * @return Illuminate\Http\Resources\Json\JsonResource
     */
    public function starred()
    {
        $emails = InboxEmail::where('starred', 1)->orderBy('created_at', 'desc')->paginate(15);

        return EmailResource::collection($emails);
    }

    /**
     * Show sent emails.
     *
     * @return Illuminate\Http\Resources\Json\JsonResource
     */
    public function sent()
    {
        $emails = InboxEmail::where('sent', 1)->orderBy('created_at', 'desc')->paginate(15);
        

        return EmailResource::collection($emails);
    }

    /**
     * Show important emails.
     *
     * @return Illuminate\Http\Resources\Json\JsonResource
     */
    public function important()
    {
        $emails = InboxEmail::where('bookmarked', 1)->orderBy('created_at', 'desc')->paginate(15);

        return EmailResource::collection($emails);
    }

    /**
     * Delete an email
     *
     * @param number                   $id
     * 
     * @return message
     */
    public function delete($id)
    {
        $email = InboxEmail::where('id', $id)->first();
        $email->delete();

        return ['message' => "Email deleted"];
    }

    /**
     * Delete multiple emails
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return message
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        InboxEmail::whereIn('id', $ids)->delete();
        return response()->json(['success' => "Emails Deleted successfully."]);

    }

    /**
     * Show deleted emails.
     *
     * @return Illuminate\Http\Resources\Json\JsonResource
     */
    public function trash()
    {
        $emails = InboxEmail::onlyTrashed()->orderBy('created_at', 'desc')->paginate(15);

        return EmailResource::collection($emails);
    }

    /**
     * Unread multiple emails
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return message
     */
    public function bulkUnread(Request $request)
    {
        $ids = $request->ids;
        InboxEmail::whereIn('id', $ids)->withTrashed()->update(['read' => 0, 'deleted_at' => null]);
        return response()->json(['success' => "Emails updated successfully."]);
    }

    /**
     * Star multiple emails
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return message
     */
    public function bulkStar(Request $request)
    {
        $ids = $request->ids;
        InboxEmail::whereIn('id', $ids)->update(['starred' => 1]);
        return response()->json(['success' => "Emails updated successfully."]);
    }

    /**
     * Make multiple emails important
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return message
     */
    public function bulkbookmark(Request $request)
    {
        $ids = $request->ids;
        InboxEmail::whereIn('id', $ids)->update(['bookmarked' => 1]);
        return response()->json(['success' => "Emails updated successfully."]);
    }
    public function getUsers()
    {
        // dd('in');
        $customer = Customer::all();
        return response()->json(['data' => $customer]);
    }
    public function getMailContent()
    {
        // dd('in mail');
        $mailContent = InboxTemplate::all();
        return response()->json(['data' => $mailContent]);
    }
    public function saveMailContent(Request $request)
    {
        // dd($request);
        

        if($request->input('id') == null){
            // dd('save');
            $mailContent = new InboxTemplate;
            $mailContent->subject = $request->input('subject');
            $mailContent->body = $request->input('html');
            $mailContent->save();
        }else{
            // dd('update');
            $mailContent = InboxTemplate::find($request->input('id'));
            $mailContent->subject = $request->input('subject');
            $mailContent->body = $request->input('html');
            $mailContent->save();
        }
        return response()->json(['data' => $mailContent]);
    }
    public function deleteMailContent($id)
    {
        // dd($id);
        $mailContent = InboxTemplate::find($id);
        $mailContent->delete();
        return response()->json(['data' => $mailContent]);
    }
    public function editMailContent($id)
    {
        // dd($id);
        $mailContent = InboxTemplate::find($id);
        // dd($mailContent);
        return response()->json(['data' => $mailContent]);
    }
}
