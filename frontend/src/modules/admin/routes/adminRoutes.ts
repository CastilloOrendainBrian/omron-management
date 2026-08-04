import type { RouteRecordRaw } from 'vue-router'
import AdminLayout from '@/modules/admin/layouts/AdminLayout.vue'

const adminRoutes: RouteRecordRaw[] = [
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
    children: [
      {
        path: '',
        name: 'admin-dashboard',
        component: () => import('@/modules/admin/views/AdminDashboardView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'users',
        name: 'admin-users',
        component: () => import('@/modules/admin/views/UserListView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'users/new',
        name: 'admin-users-new',
        component: () => import('@/modules/admin/views/UserFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'users/:id/edit',
        name: 'admin-users-edit',
        component: () => import('@/modules/admin/views/UserFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'measurements',
        name: 'admin-measurements',
        component: () => import('@/modules/admin/views/ComingSoonView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'goals',
        name: 'admin-goals',
        component: () => import('@/modules/admin/views/ComingSoonView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
    ],
  },
]

export default adminRoutes
