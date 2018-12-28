var EventBus = new Vue()

window.filterableFilters = Vue.component('filterable-filters', {
  props: {
    children: Array,
    parent: Array,
  },
  data: function () {
    return {
      parentActiveIndex: null,
      childActiveIndex: null
    }
  },
  methods: {
    handleParentClick: function (item, index) {
      this.parentActiveIndex = this.parentActiveIndex === index ? null : index
      this.emitState()
    },
    handleChildClick: function (item, index) {
      this.childActiveIndex = this.childActiveIndex === index ? null : index
      this.emitState()
    },
    emitState: function () {
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
      >
        {{ item }}
      </button>
    </div>
    <div class="filterable__children">
      <button
        class="filter-button"
        v-bind:class="{ 'active': childActiveIndex === index }"
        v-for="(item, index) in children"
        v-on:click="handleChildClick(item,index)"
      >
         {{ item }}
      </button>
    </div>
  </div>`
  ,
})

window.filterableItem = Vue.component('filterable-item', {
  props: {
    model: Object,
  },
  mounted: function () {
    var vm = this

    EventBus.$on('filter-click', function (state) {

      if (state.child) {
        var activeCat = !!vm.model.cats.filter(function (catName) {
          return catName === state.child
        })[0]
      }

      if (state.parent) {
        var activeParent = !!vm.model.parent.filter(function (parentName) {
          return parentName === state.parent
        })[0]
      }

      if (state.child && state.parent) {
        vm.$el.style.display = (activeParent && activeCat) ? 'block' : 'none'
      } else {
        vm.$el.style.display = (activeParent || activeCat) ? 'block' : 'none'
      }
      if (state.child === null && state.parent === null) {
        vm.$el.style.display = 'block'
      }
    })
  }
})


