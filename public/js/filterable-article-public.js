const EventBus = new window.Vue()

window.filterableFilters = Vue.component('filterable-filters', {
  props: {
    children: Array,
    parent: Array,
  },
  data () {
    return {
      parentActiveIndex: null,
      childActiveIndex: null
    }
  },
  methods: {
    handleParentClick (item, index) {
      this.parentActiveIndex = this.parentActiveIndex === index ? null : index
      this.emitState()
    },
    handleChildClick (item, index) {
      this.childActiveIndex = this.childActiveIndex === index ? null : index
      this.emitState()
    },
    emitState () {
      EventBus.$emit('filter-click', {
        parent: this.parentActiveIndex !== null ? this.parent[this.parentActiveIndex] : null,
        child: this.childActiveIndex !== null ? this.children[this.childActiveIndex] : null,
      })
    },
  },
  template: `
    <div>
      <div class="filterable__parent">
        <button
          class="filter-button"
          v-bind:class="{ 'active': parentActiveIndex === index }"
          v-for="(item, index) in parent"
          v-on:click="handleParentClick(item,index)"
        >{{ item }}
        </button>
      </div>
      <slot></slot>
      <div class="filterable__children">
        <button
          class="filter-button"
          v-bind:class="{ 'active': childActiveIndex === index }"
          v-for="( item, index) in children"
          v-on:click="handleChildClick(item,index)"
        >
          {{ item }}
        </button>
      </div>
    </div>
  `,
})

window.filterableItem = Vue.component('filterable-item', {
  props: {
    model: Object,
  },
  created: function () {
    EventBus.$on('filter-click', (state) => {
      let activeCat
      let activeParent
      if (state.child) {
        activeCat = !!this.model.cats.filter((catName) => catName === state.child)[0]
      }

      if (state.parent) {
        activeParent = !!this.model.parent.filter((parentName) => parentName === state.parent)[0]
      }

      if (state.child && state.parent) {
        this.$el.style.display = (activeParent && activeCat) ? 'block' : 'none'
      } else {
        this.$el.style.display = (activeParent || activeCat) ? 'block' : 'none'
      }
      if (state.child === null && state.parent === null) {
        this.$el.style.display = 'block'
      }
    })
  }
})


