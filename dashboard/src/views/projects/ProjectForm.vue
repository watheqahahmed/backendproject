<template>
  <div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8">
      <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">
        {{ isEditing ? 'تعديل المشروع' : 'إضافة مشروع جديد' }}
      </h1>

      <form @submit.prevent="saveProject" class="space-y-6">
        <div>
          <label for="projectName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">اسم المشروع</label>
          <input type="text" id="projectName" v-model="project.name" required
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white" />
        </div>

        <div>
          <label for="projectDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300">الوصف</label>
          <textarea id="projectDescription" v-model="project.description" rows="4" required
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
        </div>

        <div>
          <label for="projectImage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">صورة المشروع</label>
          <input type="file" id="projectImage" @change="handleImageUpload" accept="image/*"
            class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
          <div v-if="previewImage" class="mt-4 w-48 h-auto rounded-lg overflow-hidden shadow-lg">
            <img :src="previewImage" alt="Project Image Preview" class="w-full h-full object-cover">
          </div>
        </div>

        <div class="flex justify-end space-x-4 mt-6">
          <button type="button" @click="router.push({ name: 'ProjectList' })" class="py-2 px-6 rounded-full text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-300">
            إلغاء
          </button>
          <button type="submit" class="py-2 px-6 rounded-full bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition duration-300">
            {{ isEditing ? 'تحديث' : 'حفظ' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();

const project = ref({
  id: null,
  name: '',
  description: '',
  image: null,
});
const isEditing = ref(false);
const previewImage = ref('');
const API_URL = "http://localhost:8080/backendproject/backend/routes/api.php?request=projects";

const loadProject = async (id) => {
  try {
    const res = await axios.get(`${API_URL}&id=${id}`);
    if (res.data.success) {
      project.value.id = res.data.project.id;
      project.value.name = res.data.project.name;
      project.value.description = res.data.project.description;
      project.value.image = res.data.project.image;
      previewImage.value = `http://localhost:8080/backendproject/backend/${res.data.project.image}`;
      isEditing.value = true;
    }
  } catch (error) {
    console.error("فشل في تحميل المشروع", error);
  }
};

const saveProject = async () => {
  const formData = new FormData();
  formData.append('name', project.value.name);
  formData.append('description', project.value.description);
  
  if (project.value.image instanceof File) {
    formData.append('image', project.value.image);
  }

  try {
    let res;
    if (isEditing.value) {
      // إرسال طلب POST مع تجاوز الطريقة لتكون PUT
      res = await axios.post(`${API_URL}&id=${project.value.id}`, formData);
    } else {
      res = await axios.post(API_URL, formData);
    }
    
    // ** الشرط الأساسي الذي تم إضافته **
    if (res.data.success) {
      // إعادة التوجيه فقط عند النجاح
      router.push({ name: 'ProjectList' });
    } else {
      // عرض رسالة الخطأ من الواجهة الخلفية
      alert(res.data.message);
    }
  } catch (error) {
    console.error("فشل في حفظ المشروع", error);
    // عرض رسالة خطأ عامة في حال فشل الطلب بالكامل
    alert("حدث خطأ أثناء الاتصال بالخادم. يرجى التحقق من الخادم.");
  }
};

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    project.value.image = file;
    previewImage.value = URL.createObjectURL(file);
  } else {
    project.value.image = null;
    previewImage.value = '';
  }
};

onMounted(() => {
  if (route.params.id) {
    loadProject(route.params.id);
  }
});
</script>