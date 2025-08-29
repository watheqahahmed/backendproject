// src/stores/projects.js
import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';

export const useProjectStore = defineStore('projects', () => {
  const projects = ref([]);
  const API_URL = "http://localhost:8080/backendproject/backend/routes/api.php?request=projects";

  const fetchProjects = async () => {
    try {
      const res = await axios.get(API_URL);
      if (res.data.success) projects.value = res.data.projects;
    } catch (err) {
      console.error("فشل في جلب بيانات المشاريع", err);
      projects.value = [];
    }
  };

  return { projects, fetchProjects };
});
