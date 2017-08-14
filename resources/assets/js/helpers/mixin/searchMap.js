import { loaded } from 'vue2-google-maps'

export default {
    data: () => ({
        center: { lat: 0, lng: 0 }
    }),
    methods: {
        searchText(credential, place) {
            this.$refs.elMap.$mapCreated.then((map) => {
                const service = new google.maps.places.PlacesService(map);
                const callback = function (data, status) {

                    if (google.maps.places.PlacesServiceStatus.OK == status) {
                        const center = data[0].geometry.location.toJSON()
                        credential.address = data[0].formatted_address
                        credential.latitude = center.lat
                        credential.longitude = center.lng
                        this.center = center
                    }
                }
                service.textSearch({ query: place.name }, callback.bind(this));
            })
        },
        setGeocoder(credential, latLng) {
            const geocoder = new google.maps.Geocoder
            geocoder.geocode({ location: latLng }, (results, status) => {
                if (status === 'OK') {
                    if (results[0]) {
                        credential.latitude = results[0].geometry.location.lat()
                        credential.longitude = results[0].geometry.location.lng()
                        credential.address = results[0].formatted_address
                    }
                }
            })
        },
        setLocation(credential, place) {
            if (place.hasOwnProperty('place_id')) {
                credential.address = place.formatted_address
                credential.latitude = place.geometry.location.lat()
                credential.longitude = place.geometry.location.lng()
                this.center= place.geometry.location.toJSON()
            } else {
                //search places if you entered
                this.searchText(credential, place)
            }
        },
        loadedMaps(data) {
            loaded.then(() => {
                const input = this.$refs.elSearch.$refs.input
                input.addEventListener('input', (event) => {
                    data.address = event.target.value
                })
            })
        }
    }
}
