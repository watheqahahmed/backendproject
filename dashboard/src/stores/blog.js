// src/stores/blog.js
import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';

export const useBlogStore = defineStore('blog', () => {
  const blogPosts = ref([]);
  const API_URL = "http://localhost:8080/backendproject/backend/routes/api.php?request=blog";

  // جلب المقالات من الباك إند
  const fetchBlogPosts = async () => {
    try {
      const res = await axios.get(API_URL);
      if (res.data.success) blogPosts.value = res.data.blogPosts;
    } catch (err) {
      console.error("فشل في جلب بيانات المدونات", err);
      blogPosts.value = [];
    }
  };

  // إضافة مقال جديد مباشرة للقائمة
  const addBlogPost = (newPost) => {
    blogPosts.value.unshift(newPost);
  };

  // تحديث مقال موجود في القائمة
  const updateBlogPost = (updatedPost) => {
    const index = blogPosts.value.findIndex(p => p.id === updatedPost.id);
    if (index !== -1) blogPosts.value[index] = updatedPost;
  };

  // حذف مقال من القائمة
  const removeBlogPost = (id) => {
    blogPosts.value = blogPosts.value.filter(p => p.id !== id);
  };

  return { blogPosts, fetchBlogPosts, addBlogPost, updateBlogPost, removeBlogPost };
});
