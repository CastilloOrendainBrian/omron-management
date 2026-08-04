import type { RouteRecordRaw } from 'vue-router'
import DashboardLayout from '@/modules/dashboard/layouts/DashboardLayout.vue'

const dashboardRoutes: RouteRecordRaw[] = [
  {
    path: '/dashboard',
    component: DashboardLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'dashboard',
        component: () => import('@/modules/dashboard/views/DashboardView.vue'),
        meta: { requiresAuth: true },
      },
    ],
  },
  {
    path: '/measurements',
    name: 'measurements',
    component: () => import('@/modules/dashboard/views/ComingSoonView.vue'),
    meta: { requiresAuth: true, title: 'Mediciones' },
  },
  {
    path: '/goals',
    name: 'goals',
    component: () => import('@/modules/dashboard/views/ComingSoonView.vue'),
    meta: { requiresAuth: true, title: 'Metas' },
  },
  {
    path: '/profile',
    name: 'profile',
    component: () => import('@/modules/dashboard/views/ComingSoonView.vue'),
    meta: { requiresAuth: true, title: 'Perfil' },
  },
]

export default dashboardRoutes
