<template>
  <div class="p-4 sm:p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
    <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-700 dark:text-gray-200">إدارة المستخدمين</h2>

    <!-- جدول المستخدمين -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 shadow-sm">
        <thead class="bg-gray-100 dark:bg-gray-700">
          <tr>
            <th class="p-3 text-right text-gray-600 dark:text-gray-300">ID</th>
            <th class="p-3 text-right text-gray-600 dark:text-gray-300">الاسم</th>
            <th class="p-3 text-right text-gray-600 dark:text-gray-300">الدور</th>
            <th class="p-3 text-right text-gray-600 dark:text-gray-300">ملاحظات</th>
            <th class="p-3 text-left text-gray-600 dark:text-gray-300">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id" class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            <td class="p-3 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ user.id }}</td>
            <td class="p-3 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ user.name }}</td>
            <td class="p-3">
              <select v-model="user.role" class="bg-gray-100 dark:bg-gray-700 border rounded p-1 w-full">
                <option value="admin">مدير</option>
                <option value="editor">محرر</option>
                <option value="viewer">مشاهد</option>
              </select>
            </td>
            <td class="p-3">
              <input v-model="user.feedback" placeholder="أدخل ملاحظتك" class="border rounded p-1 w-full bg-gray-100 dark:bg-gray-700"/>
            </td>
            <td class="p-3 flex gap-5 justify-end">
              <button @click="saveUser(user)" class="px-4 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 transition text-xs sm:text-sm">
                حفظ
              </button>
              <button @click="deleteUser(user.id)" class="px-4 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition text-xs sm:text-sm">
                حذف
              </button>
            </td>
          </tr>
          <tr v-if="users.length===0">
            <td colspan="5" class="p-4 text-center text-gray-500 dark:text-gray-400">لا يوجد مستخدمون بعد</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- رسائل Toast -->
    <div v-if="toast.show" 
         :class="`fixed bottom-4 right-4 z-50 px-4 py-2 rounded shadow-lg ${toast.type==='success' ? 'bg-green-500' : toast.type==='error' ? 'bg-red-500' : 'bg-gray-400'} text-white text-sm sm:text-base transition-transform transform`">
      {{ toast.message }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const API_URL = 'http://localhost:8080/backendproject/backend/api/index.php?request=users'

const users = ref([])
const toast = ref({ show: false, message: '', type: 'success' })

function showToast(message, type='success', duration=3000) {
  toast.value = { show: true, message, type }
  setTimeout(() => toast.value.show = false, duration)
}

async function fetchUsers() {
  try {
    const res = await fetch(API_URL, { credentials: 'include' })
    if(!res.ok) throw new Error(`فشل جلب البيانات: ${res.statusText}`)
    const data = await res.json()
    users.value = data.data || data
  } catch(err) { showToast(err.message,'error'); console.error(err) }
}

async function saveUser(user){
  try{
    showToast('جارٍ حفظ التعديلات...','loading')
    const res = await fetch(`${API_URL}&id=${user.id}`, {
      method:'PUT',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ role: user.role, feedback: user.feedback }),
      credentials: 'include'
    })
    const data = await res.json()
    showToast(data.message, data.success ? 'success':'error')
    fetchUsers()
  } catch(err){ showToast('حدث خطأ أثناء الحفظ','error'); console.error(err) }
}

async function deleteUser(id){
  if(!confirm('هل أنت متأكد من الحذف؟')) return
  try{
    showToast('جارٍ حذف المستخدم...','loading')
    const res = await fetch(`${API_URL}&id=${id}`, { method:'DELETE', credentials: 'include' })
    const data = await res.json()
    showToast(data.message, data.success ? 'success':'error')
    fetchUsers()
  } catch(err){ showToast('حدث خطأ أثناء الحذف','error'); console.error(err) }
}

onMounted(fetchUsers)
</script>

<style scoped>
@media (max-width: 640px) {
  table { min-width: 600px; }
  input, select { font-size: 0.875rem; }
}

/* تحسين حركة Toast */
[v-cloak] > * { display:none }
.toast-enter-active, .toast-leave-active { transition: all 0.3s ease; }
.toast-enter-from { opacity: 0; transform: translateX(20px); }
.toast-enter-to { opacity: 1; transform: translateX(0); }
.toast-leave-from { opacity: 1; transform: translateX(0); }
.toast-leave-to { opacity: 0; transform: translateX(20px); }
</style>
