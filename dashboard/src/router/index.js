import { createRouter, createWebHistory } from "vue-router";

// استيراد الصفحات
import HomeView from "../views/HomeView.vue";
import ProjectList from "../views/projects/ProjectList.vue";
import ProjectForm from "../views/projects/ProjectForm.vue";
import BlogPostList from "../views/blog/BlogPostList.vue";
import BlogPostForm from "../views/blog/BlogPostForm.vue";
import SubmissionList from "../views/submissions/SubmissionList.vue";
import SettingsPage from "../views/settings/SettingsPage.vue";
import Users from "../views/Users.vue";
import Login from "../views/login.vue";
import SignUp from "../views/SignUp.vue";

const routes = [
  {
    path: "/",
    redirect: "/login", // فتح الموقع يذهب مباشرة لتسجيل الدخول
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
  },
  {
    path: "/signup",
    name: "SignUp",
    component: SignUp,
  },
  {
    path: "/home",
    name: "home",
    component: HomeView,
    meta: { requiresAuth: true },
  },
  {
    path: "/projects",
    name: "ProjectList",
    component: ProjectList,
    meta: { requiresAuth: true },
  },
  {
    path: "/projects/new",
    name: "ProjectNew",
    component: ProjectForm,
    meta: { requiresAuth: true },
  },
  {
    path: "/projects/edit/:id",
    name: "ProjectEdit",
    component: ProjectForm,
    props: true,
    meta: { requiresAuth: true },
  },
  {
    path: "/blog",
    name: "BlogList",
    component: BlogPostList,
    meta: { requiresAuth: true },
  },
  {
    path: "/blog/new",
    name: "BlogNew",
    component: BlogPostForm,
    meta: { requiresAuth: true },
  },
  {
    path: "/blog/edit/:id",
    name: "BlogEdit",
    component: BlogPostForm,
    props: true,
    meta: { requiresAuth: true },
  },
  {
    path: "/submissions",
    name: "SubmissionList",
    component: SubmissionList,
    meta: { requiresAuth: true },
  },
  {
    path: "/settings",
    name: "SettingsPage",
    component: SettingsPage,
    meta: { requiresAuth: true },
  },
  {
    path: "/users",
    name: "Users",
    component: Users,
    meta: { requiresAuth: true }, // الصفحة محمية ويجب تسجيل الدخول
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

// حماية الصفحات التي تحتاج تسجيل دخول
router.beforeEach((to, from, next) => {
  const isLoggedIn = localStorage.getItem("user_id"); // بعد تسجيل الدخول، نخزن user_id
  if (to.meta.requiresAuth && !isLoggedIn) {
    next("/login"); // إذا لم يسجل المستخدم الدخول، يذهب لصفحة Login
  } else {
    next();
  }
});

export default router;
