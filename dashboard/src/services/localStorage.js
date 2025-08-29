const storageKey = 'admin_dashboard_data';

const getInitialData = () => {
  const storedData = localStorage.getItem(storageKey);
  if (storedData) {
    const parsedData = JSON.parse(storedData);
    if (!parsedData.blogPosts) {
      parsedData.blogPosts = [];
    }
    if (!parsedData.projects) {
      parsedData.projects = [];
    }
    if (!parsedData.submissions) {
      parsedData.submissions = [];
    }
    return parsedData;
  }
  return {
    projects: [],
    submissions: [],
    blogPosts: [],
  };
};

let data = getInitialData();

const saveData = () => {
  localStorage.setItem(storageKey, JSON.stringify(data));
};

// تعديل هنا: قراءة حديثة دائمًا من localStorage
export const getAll = (key) => {
  const updatedData = JSON.parse(localStorage.getItem(storageKey)) || { projects: [], submissions: [], blogPosts: [] };
  return updatedData[key] || [];
};

export const getById = (key, id) => {
  const updatedData = JSON.parse(localStorage.getItem(storageKey)) || {};
  return (updatedData[key] || []).find(item => item.id == id);
};

export const create = (key, newItem) => {
  newItem.id = Date.now();
  data[key].unshift(newItem);
  saveData();
  return newItem;
};

export const update = (key, updatedItem) => {
  const index = data[key].findIndex(item => item.id === updatedItem.id);
  if (index !== -1) {
    data[key][index] = updatedItem;
    saveData();
    return true;
  }
  return false;
};

export const remove = (key, id) => {
  const initialLength = data[key].length;
  data[key] = data[key].filter(item => item.id !== id);
  saveData();
  return data[key].length < initialLength;
};

export const resetAllData = () => {
  localStorage.removeItem(storageKey);
  data = { projects: [], submissions: [], blogPosts: [] };
};
