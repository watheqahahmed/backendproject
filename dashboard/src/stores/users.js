// src/stores/users.js
import { defineStore } from 'pinia';
import axios from 'axios';
import { ref } from 'vue';

export const useUserStore = defineStore('users', () => {
  const users = ref([]);

  const fetchUsers = async () => {
    try {
      const res = await axios.get('http://localhost:8080/backendproject/backend/routes/api.php?request=users');
      if (res.data.success !== false) users.value = res.data;
      else users.value = [];
    } catch (err) {
      console.error('فشل في جلب المستخدمين', err);
      users.value = [];
    }
  };

  return { users, fetchUsers };
});
