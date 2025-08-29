// src/stores/submissions.js
import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';

export const useSubmissionStore = defineStore('submissions', () => {
  const submissions = ref([]);
  const API_URL = "http://localhost:8080/backendproject/backend/routes/api.php?request=submissions";

  const fetchSubmissions = async () => {
    try {
      const res = await axios.get(API_URL);
      if (res.data.success) submissions.value = res.data.submissions;
    } catch (err) {
      console.error("فشل في جلب بيانات الرسائل", err);
      submissions.value = [];
    }
  };

  return { submissions, fetchSubmissions };
});
