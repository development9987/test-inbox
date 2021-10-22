export default [
  { path: "/", redirect: "/dashboard" },

  {
    path: "/dashboard",
    name: "dashboard",
    component: require("./screens/dashboard").default
  },

  {
    path: "/show/:id",
    name: "show",
    component: require("./screens/show").default
  },

  {
    path: "/compose",
    name: "compose",
    component: require("./screens/compose").default
  },
  {
    path: "/compose/:emails/:template",
    name: "compose-auto",
    component: require("./screens/compose-auto").default
  },

  {
    path: "/reply/:id",
    name: "reply",
    component: require("./screens/reply").default
  },

  {
    path: "/forward/:id",
    name: "forward",
    component: require("./screens/forward").default
  },

  {
    path: "/sent",
    name: "sent",
    component: require("./screens/sent").default
  },

  {
    path: "/important",
    name: "important",
    component: require("./screens/important").default
  },

  {
    path: "/starred",
    name: "starred",
    component: require("./screens/starred").default
  },

  {
    path: "/trash",
    name: "trash",
    component: require("./screens/trash").default
  },
  {
    path: "/selectusers/:template",
    name: "selectlist",
    component: require("./screens/selectuser").default
  },
  {
    path: "/mail-content",
    name: "mailcontent",
    component: require("./screens/mail-content").default
  },
  {
    path: "/mail-content-add",
    name: "mailcontentadd",
    component: require("./screens/mail-content-add").default
  },
  {
    path: "/mail-content-edit/:id",
    name: "mailcontentedit",
    component: require("./screens/mail-content-edit").default
  },
];
