<script setup>
import { computed } from 'vue';

const props = defineProps({
    labels: { type: Array, default: () => [] },
    data: { type: Array, default: () => [] },
    color: { type: String, default: '#0082A8' },
});

const W = 640;
const H = 220;
const PAD_L = 30;
const PAD_R = 8;
const PAD_T = 14;
const PAD_B = 26;

const max = computed(() => Math.max(1, ...props.data.map(v => Number(v) || 0)));
const plotW = computed(() => W - PAD_L - PAD_R);
const plotH = computed(() => H - PAD_T - PAD_B);

const bars = computed(() => {
    const n = Math.max(1, props.data.length);
    const slot = plotW.value / n;
    const barW = Math.max(3, Math.min(28, slot * 0.6));
    return props.data.map((v, i) => {
        const value = Number(v) || 0;
        const h = (value / max.value) * plotH.value;
        const x = PAD_L + slot * i + (slot - barW) / 2;
        const y = PAD_T + plotH.value - h;
        return { x, y, w: barW, h, value, label: props.labels[i] ?? '' };
    });
});

const gridlines = computed(() =>
    [0, 0.25, 0.5, 0.75, 1].map(f => ({
        y: PAD_T + plotH.value - f * plotH.value,
        value: Math.round(max.value * f),
    }))
);

const visibleLabels = computed(() => {
    const n = props.data.length;
    const slot = plotW.value / Math.max(1, n);
    if (n <= 10) {
        return props.labels.map((l, i) => ({ text: l, x: PAD_L + slot * i + slot / 2 }));
    }
    const step = Math.ceil(n / 10);
    return props.labels.map((l, i) => ({
        text: i % step === 0 || i === n - 1 ? l : '',
        x: PAD_L + slot * i + slot / 2,
    }));
});
</script>

<template>
    <div class="w-full">
        <svg :viewBox="`0 0 ${W} ${H}`" class="w-full h-auto" preserveAspectRatio="xMidYMid meet" role="img">
            <!-- Gridlines + y values -->
            <g>
                <line
                    v-for="g in gridlines"
                    :key="'g' + g.y"
                    :x1="PAD_L"
                    :x2="W - PAD_R"
                    :y1="g.y"
                    :y2="g.y"
                    stroke="#E2E8F0"
                    stroke-width="1"
                />
                <text
                    v-for="g in gridlines"
                    :key="'t' + g.y"
                    :x="PAD_L - 6"
                    :y="g.y + 3"
                    text-anchor="end"
                    fill="#CBD5E1"
                    font-size="9"
                >{{ g.value }}</text>
            </g>

            <!-- Baseline -->
            <line :x1="PAD_L" :x2="W - PAD_R" :y1="PAD_T + plotH" :y2="PAD_T + plotH" stroke="#E2E8F0" stroke-width="1.5" />

            <!-- Bars -->
            <g>
                <rect
                    v-for="b in bars"
                    :key="'b' + b.x"
                    :x="b.x"
                    :y="b.y"
                    :width="b.w"
                    :height="Math.max(1, b.h)"
                    rx="3"
                    :fill="color"
                    :opacity="b.value === 0 ? 0.22 : 0.85"
                    class="transition-opacity duration-150 hover:opacity-100"
                >
                    <title>{{ b.label }}: {{ b.value }} visitas</title>
                </rect>
            </g>

            <!-- X labels -->
            <g>
                <text
                    v-for="(l, i) in visibleLabels"
                    :key="'x' + i"
                    :x="l.x"
                    :y="H - 6"
                    text-anchor="middle"
                    fill="#94A3B8"
                    font-size="9"
                >{{ l.text }}</text>
            </g>
        </svg>
    </div>
</template>
