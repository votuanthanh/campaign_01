require('waypoints/lib/noframework.waypoints.js')

export default {
    mounted() {
        window.Waypoint.destroyAll()
        
        const elContainer = document.querySelector('.js-waypoint-wrap')
        if (typeof(elContainer) != 'undefined' && elContainer != null) {
            var waypoint = new window.Waypoint({
                element: elContainer,
                offset: 70,
                handler: function (direction) {
                    if ($('.js-waypoint-wrap').outerWidth() > 1199) {
                        const elLeft = $('.left-home-box')
                        const elRight = $('.right-home-box')
                        const elStyle = {
                            position: 'fixed',
                            width: elLeft.parent().width(),
                            top: '70px'
                        }

                        if (direction == 'down') {
                            elLeft.css(elStyle)
                            elRight.css(elStyle)
                        } else {
                            elLeft.removeAttr('style')
                            elRight.removeAttr('style')
                        }
                    }
                }
            })
        }
    },
    beforeDestroy() {
        window.Waypoint.destroyAll()
    }
}
