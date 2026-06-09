import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import DashboardView from '@/views/DashboardView.vue'
import LoginView from '@/views/LoginView.vue'
import ProductListView from '@/views/ProductListView.vue'
import ProductDetailView from '@/views/ProductDetailView.vue'
import PriceListView from '@/views/PriceListView.vue'
import ImportView from '@/views/ImportView.vue'
import QueueView from '@/views/QueueView.vue'
import PriceRuleListView from '@/views/PriceRuleListView.vue'
import ShopListView from '@/views/ShopListView.vue'

const routes = [
  { path: '/login', name: 'login', component: LoginView, meta: { requiresAuth: false } },
  { path: '/', name: 'dashboard', component: DashboardView, meta: { requiresAuth: true } },
  { path: '/products', name: 'products', component: ProductListView, meta: { requiresAuth: true } },
  { path: '/products/:id', name: 'product-detail', component: ProductDetailView, meta: { requiresAuth: true } },
  { path: '/prices', name: 'prices', component: PriceListView, meta: { requiresAuth: true } },
  { path: '/import', name: 'import', component: ImportView, meta: { requiresAuth: true } },
  { path: '/queue', name: 'queue', component: QueueView, meta: { requiresAuth: true } },
  { path: '/rules', name: 'rules', component: PriceRuleListView, meta: { requiresAuth: true } },
  { path: '/shops', name: 'shops', component: ShopListView, meta: { requiresAuth: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, _from, next) => {
  const authStore = useAuthStore()
  if (to.meta.requiresAuth !== false && !authStore.isAuthenticated) {
    next('/login')
  } else {
    next()
  }
})

export default router
