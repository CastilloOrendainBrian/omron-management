import type { RouteRecordRaw } from 'vue-router'
import AuthLayout from '@/modules/auth/layouts/AuthLayout.vue'

const authRoutes: RouteRecordRaw[] = [
  {
    path: '/auth',
    component: AuthLayout,
    children: [
      {
        path: 'login',
        name: 'login',
        component: () => import('@/modules/auth/views/LoginView.vue'),
        meta: {
          layout: 'auth',
          requiresGuest: true,
        },
      },
      {
        path: 'register',
        name: 'register',
        component: () => import('@/modules/auth/views/ComingSoonView.vue'),
        meta: {
          layout: 'auth',
          requiresGuest: true,
        },
      },
      {
        path: 'forgot-password',
        name: 'forgot-password',
        component: () => import('@/modules/auth/views/ComingSoonView.vue'),
        meta: {
          layout: 'auth',
          requiresGuest: true,
        },
      },
    ],
  },
]

export default authRoutes
