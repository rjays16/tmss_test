const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api'

class ApiService {
  async request(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`
    
    const config = {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers,
      },
      ...options,
    }

    if (options.body && typeof options.body === 'object') {
      config.body = JSON.stringify(options.body)
    }

    const response = await fetch(url, config)
    
    if (!response.ok) {
      const error = await response.json().catch(() => ({ message: 'An error occurred' }))
      throw new Error(error.message || `HTTP ${response.status}`)
    }

    return response.json()
  }

  // Translations
  getTranslations(page = 1) {
    return this.request(`/v1/translations?page=${page}&per_page=10000`)
  }

  getTranslation(id) {
    return this.request(`/v1/translations/${id}`)
  }

  createTranslation(data) {
    return this.request('/v1/translations', {
      method: 'POST',
      body: data,
    })
  }

  updateTranslation(id, data) {
    return this.request(`/v1/translations/${id}`, {
      method: 'PUT',
      body: data,
    })
  }

  deleteTranslation(id) {
    return this.request(`/v1/translations/${id}`, {
      method: 'DELETE',
    })
  }

  searchTranslations(params = {}) {
    const queryString = new URLSearchParams(params).toString()
    return this.request(`/v1/translations/search?${queryString}`)
  }

  exportTranslations(params = {}) {
    const queryString = new URLSearchParams(params).toString()
    return this.request(`/v1/translations/export/json?${queryString}`)
  }

  // Locales
  getLocales(page = 1) {
    return this.request(`/v1/locales?page=${page}`)
  }

  getAllLocales() {
    return this.request('/v1/locales?per_page=100')
  }

  getLocale(id) {
    return this.request(`/v1/locales/${id}`)
  }

  createLocale(data) {
    return this.request('/v1/locales', {
      method: 'POST',
      body: data,
    })
  }

  updateLocale(id, data) {
    return this.request(`/v1/locales/${id}`, {
      method: 'PUT',
      body: data,
    })
  }

  deleteLocale(id) {
    return this.request(`/v1/locales/${id}`, {
      method: 'DELETE',
    })
  }

  // Tags
  getTags(page = 1) {
    return this.request(`/v1/tags?page=${page}`)
  }

  getTag(id) {
    return this.request(`/v1/tags/${id}`)
  }

  createTag(data) {
    return this.request('/v1/tags', {
      method: 'POST',
      body: data,
    })
  }

  updateTag(id, data) {
    return this.request(`/v1/tags/${id}`, {
      method: 'PUT',
      body: data,
    })
  }

  deleteTag(id) {
    return this.request(`/v1/tags/${id}`, {
      method: 'DELETE',
    })
  }
}

export const api = new ApiService()
export default api
