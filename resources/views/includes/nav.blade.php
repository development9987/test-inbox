<div class="email-leftbar card">
    <router-link to="/compose" class="btn btn-purple-primary btn-block">Compose</router-link>
    {{-- <ul class="nav">
        <li class="nav-item">
            <router-link class="nav-link nav-link-o" to="/dashboard">
                <i class="fa fa-inbox"></i> Inbox
                <span class="badge badge-danger">{{ $emailCount }}</span>
            </router-link>
        </li>
        <li class="nav-item">
            <router-link class="nav-link nav-link-o" to="/starred">
                <i class="fa fa-star"></i> Stared
            </router-link>
        </li>
        <li class="nav-item">
            <router-link class="nav-link nav-link-o" to="/sent"><i class="fa fa-rocket"></i> Sent</router-link>
        </li>
        <li class="nav-item">
            <router-link class="nav-link nav-link-o" to="/trash"><i class="fas fa-trash"></i> Trash</router-link>
        </li>
        <li class="nav-item">
            <router-link class="nav-link nav-link-o" to="/important"><i class="fa fa-bookmark"></i> Important</router-link>
        </li>
    </ul> --}}
    <div class="mail-list mt-3">
        <router-link class="nav-link nav-link-o" to="/dashboard">
            <i class="fa fa-inbox"></i> Inbox
            <span class="badge badge-danger">{{ $emailCount }}</span>
        </router-link>
        <router-link class="nav-link nav-link-o" to="/starred">
            <i class="fa fa-star"></i> Stared
        </router-link>
        <router-link class="nav-link nav-link-o" to="/sent"><i class="fa fa-rocket"></i> Sent</router-link>
        <router-link class="nav-link nav-link-o" to="/trash"><i class="fas fa-trash"></i> Trash</router-link>
        <router-link class="nav-link nav-link-o" to="/important"><i class="fa fa-bookmark"></i> Important</router-link>
    </div>
</div>