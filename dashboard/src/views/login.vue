<template>
  <div
    class="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900"
  >
    <div
      class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md"
    >
      <h2 class="text-2xl font-bold mb-6 text-gray-700 dark:text-gray-200">
        تسجيل الدخول
      </h2>

      <!-- البريد الإلكتروني -->
      <div class="mb-4">
        <input
          v-model="email"
          type="email"
          placeholder="البريد الإلكتروني"
          @input="validateEmail"
          class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-700"
        />
        <p v-if="emailError" class="text-red-500 text-sm mt-1">
          {{ emailError }}
        </p>
      </div>

      <!-- كلمة المرور -->
      <div class="mb-4">
        <input
          v-model="password"
          type="password"
          placeholder="كلمة المرور"
          @input="validatePassword"
          class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-700"
        />
        <p v-if="passwordError" class="text-red-500 text-sm mt-1">
          {{ passwordError }}
        </p>
        <p class="text-gray-500 text-xs mt-1">
          كلمة المرور يجب أن تكون 5 أحرف على الأقل
        </p>
      </div>

      <button
        @click="loginUser"
        :disabled="emailError || passwordError || !email || !password"
        class="w-full bg-indigo-500 text-white py-2 rounded hover:bg-indigo-600 transition mb-4 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        تسجيل الدخول
      </button>

      <p class="text-sm text-gray-500 dark:text-gray-400">
        لا تملك حساب؟
        <router-link to="/signup" class="text-indigo-500 hover:underline"
          >إنشاء حساب</router-link
        >
      </p>

      <!-- Toast -->
      <div
        v-if="toast.show"
        :class="`mt-4 px-4 py-2 rounded ${
          toast.type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white`"
      >
        {{ toast.message }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const email = ref("");
const password = ref("");
const toast = ref({ show: false, message: "", type: "success" });

const emailError = ref("");
const passwordError = ref("");

const API_URL =
  "http://localhost:8080/backendproject/backend/api/index.php?request=login";

function showToast(message, type = "success", duration = 3000) {
  toast.value = { show: true, message, type };
  setTimeout(() => (toast.value.show = false), duration);
}

// التحقق من البريد الإلكتروني (بسيط)
function validateEmail() {
  emailError.value = email.value.includes("@")
    ? ""
    : "البريد الإلكتروني غير صالح";
}

// التحقق من كلمة المرور
function validatePassword() {
  passwordError.value =
    password.value.length < 5 ? "كلمة المرور قصيرة جداً" : "";
}

// تسجيل الدخول
async function loginUser() {
  if (
    emailError.value ||
    passwordError.value ||
    !email.value ||
    !password.value
  ) {
    showToast("الحقول غير صالحة", "error");
    return;
  }

  try {
    const res = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email: email.value, password: password.value }),
      credentials: "include",
    });

    const text = await res.text();
    let data;
    try {
      data = JSON.parse(text);
    } catch (e) {
      showToast("خطأ في الرد من السيرفر", "error");
      console.error(text);
      return;
    }

    showToast(data.message, data.success ? "success" : "error");

    if (data.success) {
      localStorage.setItem("user", JSON.stringify(data.user));
      localStorage.setItem("role", data.user.role);
      router.push("/users");
    }
  } catch (err) {
    showToast("حدث خطأ", "error");
    console.error(err);
  }
}
</script>

<style scoped>
input:focus {
  outline: none;
  border-color: #6366f1;
}
button:focus {
  outline: none;
}
</style>
