import axios from 'axios'
import { processObserver } from 'asdoriacore/common/utils/intersectionObserver'
import eventBus from 'asdoriacore/common/utils/eventBusVanilla'
import LocaleRouter from '../../../../../assets/js/common/routing/LocaleRouter'
import events from '../../../../../assets/js/common/constants/events'

class InfiniteScroll {
  constructor (container, loading) {
    this.container = container
    this.loading   = loading
    this.page      = 1
    this.maxPages  = this.container.dataset.maxPages
    this.route     = this.container.dataset.route ?? null
  }

  fetchData () {
    const slug = window.location.pathname.split('/').pop()
    if (!slug) return

    const queriesString = decodeURI(window.location.search)
    const pageNumber    = { 'page': this.page }

    const path = LocaleRouter.generate(this.route, { slug }) + queriesString

    axios.get(path, { params: { ...pageNumber } })
      .then(({ data: DOM }) => {
        const div      = document.createElement('div')
        div.innerHTML  = DOM
        const children = [...div.children]
        children.forEach(child => {
          this.container.appendChild(child)
        })

        this.initLoading()

        eventBus.dispatchEvent(events.INFINITE_SCROLL_LOADED, { 'container': this.container })
      })
      .catch(e => {
        if (e.response?.status === 404) {
          // this.loading.classList.add('hidden')
          this.loading.style.display = 'none'
        }
      })
  }

  initLoading () {
    // this.loading.classList.remove('hidden')
    this.loading.style.display = 'block'

    const callback = () => {
      this.page++
      if (this.page > this.maxPages) {
        // this.loading.classList.add('hidden')
        this.loading.style.display = 'none'
        return
      }
      this.fetchData()
    }

    processObserver({ callback, elementsObserved: [this.loading] })
  }

  init () {
    this.initLoading()
  }
}

export default () => {
  const container = document.querySelector('.js-infinite-scroll')
  const loading   = document.querySelector('.js-infinite-scroll-loading')
  if (!container || !loading) return

  const instanceInfiniteScroll = new InfiniteScroll(container, loading)
  instanceInfiniteScroll.init()
}
