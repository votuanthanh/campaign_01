<template>
    <div class="post-additional-info inline-items" v-if="type == 'likeInfo'">
    {{ model + modelId }}
        <span>
            <a href="javascript:void(0)"
                class="post-add-icon inline-items"
                @click="likeActivity({ model: model, modelId: modelId, userId: user.id })"
            >
                <svg class="olymp-heart-icon">
                    <use xlink:href="/frontend/icons/icons.svg#olymp-heart-icon"
                        :class="{ fill: checkLiked[model + modelId] }">
                    </use>
                </svg>
            </a>
            <span class="span-show-name" @click="showUsersLiked(like[model + modelId].data)">
                <ul class="show-name" v-if="like[model + modelId].total">
                    <li v-for="(item, key, index) in like[model + modelId].data" v-if="key < 5">
                        <pre>{{ item.user.name }}</pre>
                    </li>
                    <li v-if="like[model + modelId].total >= 5">
                        <pre>{{ $t('post.like.and') }} {{ like[model + modelId].total - 5 }} {{ $t('post.like.more_like') }}</pre>
                    </li>
                </ul>
                {{ like[model + modelId].total }}
            </span>
        </span>
        <ul class="friends-harmonic" v-if="like[model + modelId].total">
            <li v-for="(item, key, index) in like[model + modelId].data" v-if="key < 4">
                <a>
                    <img :src="item.user.url_file" alt="friend">
                </a>
            </li>
        </ul>
        <div class="names-people-likes" v-if="!like[model + modelId].total">
            {{ $t('post.like.like_first') }}
        </div>
        <div class="names-people-likes" v-else-if="like[model + modelId].total == 1">
            <a href="#">{{ like[model + modelId].data[0].user.name }} </a> {{ $t('post.like.liked_this') }}
        </div>
        <div class="names-people-likes" v-else-if="like[model + modelId].total == 2">
            <a href="#">{{ like[model + modelId].data[0].user.name }}</a> {{ $t('post.like.and') }}
            <a href="#">{{ like[model + modelId].data[1].user.name }}</a> {{ $t('post.like.liked_this') }}
        </div>
        <div class="names-people-likes" v-else="like[model + modelId].total > 2">
            <a href="#">{{ like[model + modelId].data[0].user.name }}</a>,
            <a href="#">{{ like[model + modelId].data[1].user.name }}</a>
            <br>{{ $t('post.like.and') }} {{ like[model + modelId].total - 2 }} {{ $t('post.like.more_like') }}
        </div>
        <div :class="{ 'mrg-top': (like[model + modelId].total < 3), 'comments-shared': true }">
            <a href="#" class="post-add-icon inline-items">
                <svg class="olymp-speech-balloon-icon">
                    <use xlink:href="/frontend/icons/icons.svg#olymp-speech-balloon-icon"></use>
                </svg>
                <span>{{ numberOfComments }}</span>
            </a>
            <a href="#" class="post-add-icon inline-items">
                <svg class="olymp-share-icon">
                    <use xlink:href="/frontend/icons/icons.svg#olymp-share-icon"></use>
                </svg>
                <span>0</span>
            </a>
        </div>


    </div>

    <span v-else-if="type == 'likeComment'" class="info-like">
        <a href="javascript:void(0)"
            class="post-add-icon inline-items"
            @click="likeActivity({ model: model, modelId: modelId, userId: user.id })"
        >
            <svg class="olymp-heart-icon">
                <use xlink:href="/frontend/icons/icons.svg#olymp-heart-icon"
                    :class="{ fill: checkLiked[model + modelId] }">
                </use>
            </svg>
        </a>
        <span class="span-show-name">
            <ul class="show-name" v-if="like[model + modelId].total">
                <li v-for="(item, key, index) in like[model + modelId].data" v-if="key < 5">
                    <pre>{{ item.user.name }}</pre>
                </li>
                <li v-if="like[model + modelId].total >= 5">
                    <pre>{{ $t('post.like.and') }} {{ like[model + modelId].total - 5 }} {{ $t('post.like.more_like') }}</pre>
                </li>
            </ul>
            {{ like[model + modelId].total }}
        </span>
    </span>

    <a href="javascript:void(0)" v-else
        :class="{ liked: checkLiked[model + modelId], btn: true, 'btn-control': true }"
        @click="likeActivity({ model: model, modelId: modelId, userId: user.id })"
    >
        <svg class="olymp-like-post-icon">
            <use xlink:href="/frontend/icons/icons.svg#olymp-like-post-icon"></use>
        </svg>
    </a>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    props: {
        type: {
            type: String,
            default: '',
        },
        likes: '',
        model: '',
        modelId: '',
        checkLike: true,
        numberOfComments: 0,
    },
    created() {
        this.setLike({
            like: this.likes,
            modelId: this.modelId,
            model: this.model,
            checkLiked: this.checkLike
        })
    },
    computed: {
        ...mapState('auth', {
            user: state => state.user
        }),
        ...mapState('like', ['like', 'checkLiked']),
    },
    methods: {
        ...mapActions('like', ['setLike', 'likeActivity', 'showUsersLiked']),
    }
}
</script>

<style lang="scss" scoped>
    .liked {
        background: #ff5e3a !important;
    }
    .mrg-top {
        margin-top: 0px !important;
    }
    .fill {
        fill: #ff5e3a;
    }
    .info-like {
        margin-right: 5px;
        .post-add-icon.inline-items {
            margin-right: 0px;
        }
    }
    .span-show-name {
        position: relative;
        :after {
            content: '';
            position: absolute;
            border-style: solid;
            border-width: 0 6px 6px 0;
            border-color: transparent #3f4257;
            display: block;
            width: 0;
            z-index: 1;
            bottom: -6px;
            left: 28px;
        }
        .show-name {
            bottom: 10px;
            padding: 10px;
            display: none;
            position: absolute;
            background: #3f4257;
            margin-bottom: 13px;
            border-radius: 5px;
            li {
                padding: initial;
                border-bottom: initial;
                background-color: initial;
            }
        }
        &:hover {
            cursor: pointer;
            .show-name {
                display: block;
            }
        }
    }

    pre {
        font-family: Roboto, -apple-system, system-ui, BlinkMacSystemFont,
            "Segoe UI", "Helvetica Neue", Arial, sans-serif !important;
        font-size: 0.812rem !important;
        color: #edf2f6;
        margin-bottom: 0px;
    }

</style>
