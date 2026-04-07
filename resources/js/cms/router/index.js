import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/cms/stores/auth'

import LoginPage from '@/cms/pages/LoginPage.vue'
import AdminLayout from '@/cms/layouts/AdminLayout.vue'
import DashboardPage from '@/cms/pages/DashboardPage.vue'
import PostsIndexPage from '@/cms/pages/posts/PostsIndexPage.vue'
import PostCreatePage from '@/cms/pages/posts/PostCreatePage.vue'
import PostEditPage from '@/cms/pages/posts/PostEditPage.vue'
import PagesIndexPage from '@/cms/pages/pages/PagesIndexPage.vue'
import PageCreatePage from '@/cms/pages/pages/PageCreatePage.vue'
import PageEditPage from '@/cms/pages/pages/PageEditPage.vue'
import CategoriesIndexPage from '@/cms/pages/categories/CategoriesIndexPage.vue'
import TagsIndexPage from '@/cms/pages/tags/TagsIndexPage.vue'
import MediaIndexPage from '@/cms/pages/media/MediaIndexPage.vue'
import SettingsPage from '@/cms/pages/settings/SettingsPage.vue'

const routes = [
  {
    path: '/admin/login',
    name: 'admin.login',
    component: LoginPage,
    meta: { guestOnly: true },
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'admin.dashboard',
        component: DashboardPage,
      },
      {
        path: 'posts',
        name: 'admin.posts.index',
        component: PostsIndexPage,
      },
      {
        path: 'posts/create',
        name: 'admin.posts.create',
        component: PostCreatePage,
      },
      {
        path: 'posts/:id/edit',
        name: 'admin.posts.edit',
        component: PostEditPage,
        props: true,
      },
      {
        path: 'pages',
        name: 'admin.pages.index',
        component: PagesIndexPage,
      },
      {
        path: 'pages/create',
        name: 'admin.pages.create',
        component: PageCreatePage,
      },
      {
        path: 'pages/:id/edit',
        name: 'admin.pages.edit',
        component: PageEditPage,
        props: true,
      },
      {
        path: 'categories',
        name: 'admin.categories.index',
        component: CategoriesIndexPage,
      },
      {
        path: 'tags',
        name: 'admin.tags.index',
        component: TagsIndexPage,
      },
      {
        path: 'media',
        name: 'admin.media.index',
        component: MediaIndexPage,
      },
      {
        path: 'settings',
        name: 'admin.settings',
        component: SettingsPage,
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to) => {
  const auth = useAuthStore()

  if (auth.token && !auth.user) {
    try {
      await auth.fetchMe()
    } catch {
      auth.clearAuth()
    }
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return { name: 'admin.login' }
  }

  if (to.meta.guestOnly && auth.isAuthenticated) {
    return { name: 'admin.dashboard' }
  }

  return true
})

export default router
