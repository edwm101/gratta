<template>
  <div>
    <v-list-item-title>
      <ul
        class="v-breadcrumbs new px-0 v-breadcrumbs--large  theme--light py-1 overflow-x-auto"
        itemscope
        itemtype="https://schema.org/BreadcrumbList"
      >
        <template v-for="(item, key) in elements">
          <li
            itemprop="itemListElement"
            itemscope
            itemtype="https://schema.org/ListItem"
            :key="key"
            v-if="item.name"
          >
            <nuxt-link
              itemprop="item"
              :to="{ ...item.path }"
              class="v-breadcrumbs__item font-weight-bold title"
              :class="{
                'v-breadcrumbs__item--disabled':
                  key == elements.length - 1 &&
                  elements.length > 1 &&
                  !item.show,
              }"
            >
              <span itemprop="name">{{ item.name }}</span>
            </nuxt-link>
            <meta itemprop="position" :content="key + 1" />
          </li>
          <li
            class="v-breadcrumbs__divider"
            v-if="item.name && key != elements.length - 1"
            :key="'i' + key"
          >
            /
          </li>
        </template>
      </ul>
    </v-list-item-title>
  </div>
</template>

<script>
export default {
  props: {
    items: {
      type: Array,
    },
  },
  data() {
    return {
      elements: [],
    }
  },
  watch: {
    items(val) {
      this.elements = val.filter((item) => {
        return item.name
      })
    },
  },
  created() {
    this.elements = this.items.filter((item) => {
      return item.name
    })
  },
}
</script>