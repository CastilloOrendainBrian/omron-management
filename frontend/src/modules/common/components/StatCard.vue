<script setup lang="ts">
interface Trend {
  value: string
  tone: 'positive' | 'negative' | 'neutral'
}

interface Props {
  title: string
  value: string | number
  trend?: Trend
}

withDefaults(defineProps<Props>(), {
  trend: undefined,
})

const trendClasses: Record<Trend['tone'], string> = {
  positive: 'bg-emerald-500/10 text-emerald-500',
  negative: 'bg-rose-500/10 text-rose-500',
  neutral: 'bg-stone-200 text-stone-600',
}
</script>

<template>
  <div class="bg-white rounded-md border border-stone-200 p-6 shadow-sm shadow-stone-200/60">
    <div class="flex justify-between mb-6">
      <div>
        <div class="flex items-center mb-1">
          <div class="text-2xl font-semibold text-stone-800">{{ value }}</div>
          <span
            v-if="trend"
            :class="[
              'ml-2 px-1 py-0.5 rounded text-[12px] font-semibold leading-none',
              trendClasses[trend.tone],
            ]"
          >
            {{ trend.value }}
          </span>
        </div>
        <div class="text-sm font-medium text-stone-500">{{ title }}</div>
      </div>

      <slot name="action" />
    </div>

    <slot name="footer" />
  </div>
</template>
