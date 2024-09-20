import { defineStore } from 'pinia'
import { uniqueId } from 'lodash'

export const useAlertsStore = defineStore('alerts', {
    state: () => ({
        alerts: []
    }),
    actions: {
        add(title, description = null, type = 'default') {
            const index = this.alerts.push({
                title,
                description,
                type,
                hide: false,
                timer: null,
                id: uniqueId()
            })

            this.setTimer(index - 1)
        },

        remove(index) {
            if (this.alerts[index]) {
                this.alerts[index].hide = true
                setTimeout(() => this.alerts.splice(index, 1), 100)
            } else {
                this.alerts.shift()
            }
        },

        removeTimer(index) {
            clearTimeout(this.alerts[index].timer)
        },

        setTimer(index) {
            if (this.alerts[index]) {
                this.alerts[index].timer = setTimeout(() => { this.remove(index) }, 5000)
            }
        }
    }
})
