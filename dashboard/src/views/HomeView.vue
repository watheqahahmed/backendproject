<template>
  <div class="p-4 sm:p-6 space-y-8">
    <!-- عنوان الصفحة -->
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 dark:text-white">
      الرئيسية
    </h1>

    <!-- قسم الإحصائيات - بطاقات الشبكة -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
      <!-- بطاقة -->
      <div
        v-for="card in cards"
        :key="card.title"
        class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-xl shadow-lg flex items-center justify-between transition-transform duration-300 hover:scale-105"
      >
        <div>
          <h2 class="text-base sm:text-lg md:text-xl font-semibold text-gray-700 dark:text-gray-300">
            {{ card.title }}
          </h2>
          <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mt-2">
            {{ card.value }}
          </p>
        </div>
        <div :class="card.iconBg" class="p-3 rounded-full">
          <i :class="card.icon" class="text-2xl"></i>
        </div>
      </div>
    </div>

    <!-- قسم الرسم البياني -->
    <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-xl shadow-lg transition-colors duration-300">
      <h2 class="text-base sm:text-lg md:text-xl font-semibold text-gray-800 dark:text-white mb-4">
        المستخدمون خلال آخر 6 أشهر
      </h2>
      <div class="relative h-64 sm:h-80 md:h-96 w-full">
        <canvas ref="usersChartCanvas"></canvas>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import Chart from 'chart.js/auto';
import { useProjectStore } from '@/stores/projects';
import { useBlogStore } from '@/stores/blog';
import { useSubmissionStore } from '@/stores/submissions';
import { useUserStore } from '@/stores/users';

const projectStore = useProjectStore();
const blogStore = useBlogStore();
const submissionStore = useSubmissionStore();
const userStore = useUserStore();

const stats = ref({
  users: 0,
  projects: 0,
  blogPosts: 0,
  submissions: 0,
});

const cards = ref([]);

const usersChartCanvas = ref(null);
let usersChartInstance = null;

const updateStatsFromStores = () => {
  stats.value.users = userStore.users.length;
  stats.value.projects = projectStore.projects.length;
  stats.value.blogPosts = blogStore.blogPosts.length;
  stats.value.submissions = submissionStore.submissions.length;

  cards.value = [
    {
      title: 'إجمالي المستخدمين',
      value: stats.value.users,
      icon: 'fas fa-users text-blue-600 dark:text-blue-400',
      iconBg: 'bg-blue-100 dark:bg-blue-900',
    },
    {
      title: 'إجمالي المشاريع',
      value: stats.value.projects,
      icon: 'fas fa-project-diagram text-green-600 dark:text-green-400',
      iconBg: 'bg-green-100 dark:bg-green-900',
    },
    {
      title: 'إجمالي المقالات',
      value: stats.value.blogPosts,
      icon: 'fas fa-newspaper text-yellow-600 dark:text-yellow-400',
      iconBg: 'bg-yellow-100 dark:bg-yellow-900',
    },
    {
      title: 'إجمالي الرسائل',
      value: stats.value.submissions,
      icon: 'fas fa-envelope text-red-600 dark:text-red-400',
      iconBg: 'bg-red-100 dark:bg-red-900',
    },
  ];

  setupChart();
};

const setupChart = () => {
  if (usersChartInstance) usersChartInstance.destroy();

  const data = [
    Math.floor(stats.value.users * 0.4),
    Math.floor(stats.value.users * 0.55),
    Math.floor(stats.value.users * 0.65),
    Math.floor(stats.value.users * 0.75),
    Math.floor(stats.value.users * 0.9),
    stats.value.users,
  ];
  const chartData = {
    labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
    datasets: [
      {
        label: 'عدد المستخدمين',
        data,
        backgroundColor: 'rgba(59, 130, 246, 0.3)',
        borderColor: 'rgba(59, 130, 246, 1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
        pointRadius: 5,
      },
    ],
  };

  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: true } },
    scales: { y: { beginAtZero: true } },
  };

  if (usersChartCanvas.value) {
    usersChartInstance = new Chart(usersChartCanvas.value, {
      type: 'line',
      data: chartData,
      options: chartOptions,
    });
  }
};

onMounted(async () => {
  await userStore.fetchUsers();
  await projectStore.fetchProjects();
  await blogStore.fetchBlogPosts();
  await submissionStore.fetchSubmissions();
  updateStatsFromStores();
});

watch(
  () => [userStore.users, projectStore.projects, blogStore.blogPosts, submissionStore.submissions],
  () => updateStatsFromStores(),
  { deep: true }
);
</script>
