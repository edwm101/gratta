import Vue from 'vue'
import VueDraggable from 'vue-draggable'
import { VueDraggableDirective } from 'vue-draggable'

Vue.use(VueDraggable)

export default {
  directives: {
    dragAndDrop: VueDraggableDirective,
  },
}
