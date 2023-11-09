import axios from 'axios'
import { processObserver } from './utils/intersectionObserver'

class InfiniteScrollTable {
  constructor ({ container, loading, maxPages, path }) {
    this.container = container
    this.loading   = loading
    this.page      = 1
    this.path      = path
    this.maxPages  = maxPages
    this.tableBody = this.container.querySelector('table > tbody')
  }

  fetchData () {
    axios.get(this.path, { params: { 'page': this.page } })
      .then(({ data: DOM }) => {
        const tbody = document.createElement('tbody')
        tbody.innerHTML = DOM

        const children = [...tbody.children]
        children.forEach(child => {
          this.tableBody.appendChild(child)
        })

        this.initLoading()
      })
      .catch(e => {
        if (e.response?.status === 404) {
          this.loading.style.display = 'none'
        }
      })
  }

  initLoading () {
    this.loading.style.display = 'block'

    const onObservation = () => {
      this.page++
      if (this.page > this.maxPages) {
        this.loading.style.display = 'none'
        return
      }
      this.fetchData()
    }

    processObserver({ callback: onObservation, elementsObserved: [this.loading] })
  }

  init () {
    this.initLoading()
  }
}

export default () => {
  const container = document.querySelector('.js-infinite-scroll')
  const loading   = document.querySelector('.js-infinite-scroll-loading')
  if (!container || !loading) return

  const path = container.dataset?.path ?? null
  if (!path) return

  const maxPages = container.dataset?.maxPages ?? null
  if (!maxPages) return

  const instanceInfiniteScroll = new InfiniteScrollTable({
    container,
    loading,
    path,
    maxPages,
  })
  instanceInfiniteScroll.init()
}
