<template>
  <aside class="bg-gray-800 dark:bg-gray-900 text-white w-64 min-h-screen p-4">
    <h1 class="text-2xl font-bold mb-8 text-white">Admin Panel</h1>
    <nav>
      <ul>
        <li>
          <router-link to="/" class="flex items-center p-2 mb-2 rounded-lg transition-colors hover:bg-gray-700" active-class="bg-indigo-600 hover:bg-indigo-600">
            <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Dashboard</span>
          </router-link>
        </li>
        <li>
          <router-link to="/projects" class="flex items-center p-2 mb-2 rounded-lg transition-colors hover:bg-gray-700" active-class="bg-indigo-600 hover:bg-indigo-600">
            <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l-2 2L4 5m0 0l3 3m-3-3l-2 2m0 0v11a2 2 0 002 2h10a2 2 0 002-2v-5m-7-9l-2-2m2 2l-2-2m2 2l2 2m-2-2v11a2 2 0 002 2h10a2 2 0 002-2v-5M7 11h7a2 2 0 012 2v2a2 2 0 01-2 2h-7a2 2 0 01-2-2v-2a2 2 0 012-2z"/></svg>
            <span>Projects</span>
          </router-link>
        </li>
        <li>
          <router-link to="/submissions" class="flex items-center p-2 mb-2 rounded-lg transition-colors hover:bg-gray-700" active-class="bg-indigo-600 hover:bg-indigo-600">
            <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-2 11H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2z"/></svg>
            <span>Submissions</span>
          </router-link>
        </li>
        <li>
          <router-link to="/settings" class="flex items-center p-2 mb-2 rounded-lg transition-colors hover:bg-gray-700" active-class="bg-indigo-600 hover:bg-indigo-600">
            <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"/></svg>
            <span>Settings</span>
          </router-link>
        </li>
      </ul>
    </nav>
  </aside>
</template>