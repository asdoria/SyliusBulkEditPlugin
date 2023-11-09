const defaultOptions = { root: null, rootMargin: '0px' }

export const processObserver = ({
    callback,
    callbackOut = () => {},
    elementsObserved,
    options: _options = {},
    unobserved = true,
}) => {
    const options = { ...defaultOptions, ..._options }

    const createObserver = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                callback(entry)
                if (unobserved) {
                    observer.unobserve(entry.target)
                }
                return
            }

            callbackOut(entry)
        })
    }

    const observer = new IntersectionObserver(createObserver, options);

    [...elementsObserved].forEach(el => {
        observer.observe(el)
    })

    return observer;
}
