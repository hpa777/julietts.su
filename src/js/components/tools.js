export const checkIncludePoint = (el, clientX, clientY) => {
    const rect = el.getBoundingClientRect()
    return clientX >= rect.left && clientX <= rect.right && clientY >= rect.top && clientY <= rect.bottom
}
