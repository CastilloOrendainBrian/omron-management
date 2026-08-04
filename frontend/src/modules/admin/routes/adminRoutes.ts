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
        path: 'profiles',
        name: 'admin-profiles',
        component: () => import('@/modules/admin/profiles/views/UserProfileListView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'profiles/new',
        name: 'admin-profiles-new',
        component: () => import('@/modules/admin/profiles/views/UserProfileFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'profiles/:id/edit',
        name: 'admin-profiles-edit',
        component: () => import('@/modules/admin/profiles/views/UserProfileFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'goals',
        name: 'admin-goals',
        component: () => import('@/modules/admin/goals/views/GoalListView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'goals/new',
        name: 'admin-goals-new',
        component: () => import('@/modules/admin/goals/views/GoalFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'goals/:id/edit',
        name: 'admin-goals-edit',
        component: () => import('@/modules/admin/goals/views/GoalFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'devices',
        name: 'admin-devices',
        component: () => import('@/modules/admin/devices/views/DeviceListView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'devices/new',
        name: 'admin-devices-new',
        component: () => import('@/modules/admin/devices/views/DeviceFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'devices/:id/edit',
        name: 'admin-devices-edit',
        component: () => import('@/modules/admin/devices/views/DeviceFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'measurements',
        name: 'admin-measurements',
        component: () => import('@/modules/admin/views/ComingSoonView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
    ],
  },
]

export default adminRoutes
