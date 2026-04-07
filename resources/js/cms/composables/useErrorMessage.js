export function useErrorMessage() {
  const firstError = (errors, key) => {
    if (!errors || !errors[key] || !errors[key].length) {
      return ''
    }

    return errors[key][0]
  }

  return {
    firstError,
  }
}
