export default {
    methods: {
        shortenText: (text, length = 50) => {
            return text.substring(0, length) + '...'
        },

        getFullName: user => {
            if (!user) {
                return ''
            }

            return `${user.name_first} ${user.name_last}`
        },

        capitalize: string => {
            if (typeof string !== 'string') {
                return ''
            }

            return string.charAt(0).toUpperCase() + string.slice(1)
        }
    }
}
