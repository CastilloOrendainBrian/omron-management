import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import authRoutes from '@/modules/auth/routes/authRoutes'
import adminRoutes from '@/modules/admin/routes/adminRoutes'
import dashboardRoutes from '@/modules/dashboard/routes/dashboardRoutes'

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/HomeView.vue'),
  },
  ...authRoutes,
  ...dashboardRoutes,
  ...adminRoutes,
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('@/views/NotFoundView.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router
