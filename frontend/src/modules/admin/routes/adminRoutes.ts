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
        path: 'anthropometric',
        name: 'admin-anthropometric',
        component: () =>
          import('@/modules/admin/anthropometric/views/AnthropometricMeasurementListView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'anthropometric/new',
        name: 'admin-anthropometric-new',
        component: () =>
          import('@/modules/admin/anthropometric/views/AnthropometricMeasurementFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'anthropometric/:id/edit',
        name: 'admin-anthropometric-edit',
        component: () =>
          import('@/modules/admin/anthropometric/views/AnthropometricMeasurementFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'body-composition',
        name: 'admin-body-composition',
        component: () =>
          import('@/modules/admin/body-composition/views/BodyCompositionMeasurementListView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'body-composition/new',
        name: 'admin-body-composition-new',
        component: () =>
          import('@/modules/admin/body-composition/views/BodyCompositionMeasurementFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'body-composition/:id/edit',
        name: 'admin-body-composition-edit',
        component: () =>
          import('@/modules/admin/body-composition/views/BodyCompositionMeasurementFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'skinfold',
        name: 'admin-skinfold',
        component: () => import('@/modules/admin/skinfold/views/SkinfoldMeasurementListView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'skinfold/new',
        name: 'admin-skinfold-new',
        component: () => import('@/modules/admin/skinfold/views/SkinfoldMeasurementFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'skinfold/:id/edit',
        name: 'admin-skinfold-edit',
        component: () => import('@/modules/admin/skinfold/views/SkinfoldMeasurementFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'skinfold-protocols',
        name: 'admin-skinfold-protocols',
        component: () =>
          import('@/modules/admin/skinfold-protocol/views/SkinfoldProtocolListView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'skinfold-protocols/new',
        name: 'admin-skinfold-protocols-new',
        component: () =>
          import('@/modules/admin/skinfold-protocol/views/SkinfoldProtocolFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'skinfold-protocols/:id/edit',
        name: 'admin-skinfold-protocols-edit',
        component: () =>
          import('@/modules/admin/skinfold-protocol/views/SkinfoldProtocolFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'skinfold-sites',
        name: 'admin-skinfold-sites',
        component: () => import('@/modules/admin/skinfold-site/views/SkinfoldSiteListView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'skinfold-sites/new',
        name: 'admin-skinfold-sites-new',
        component: () => import('@/modules/admin/skinfold-site/views/SkinfoldSiteFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
      {
        path: 'skinfold-sites/:id/edit',
        name: 'admin-skinfold-sites-edit',
        component: () => import('@/modules/admin/skinfold-site/views/SkinfoldSiteFormView.vue'),
        meta: { requiresAuth: true, roles: ['admin', 'super-admin'] },
      },
    ],
  },
]

export default adminRoutes
