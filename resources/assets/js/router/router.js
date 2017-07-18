// Here is API router from server to avoid hardcode while development is

export const getUser = 'user'
export const handleLogin = 'login'
export const logout = 'logout'
export const register = 'register'
export const storeCampaign = 'campaign'
export const tags = 'campaign/get/tags'
export const changePassword = 'settings/password'
export const updateProfile = 'settings/profile'
export const sendMessage = 'send-message'
export const receiveMessage = 'receive-message'
export const showMessage = 'show-message'
export const follow = 'list-user-following'
export const updateHeaderImage = 'settings/header-photo'
export const updateAvatar = 'settings/avatar'
export const uploadImages = 'settings/upload-files/'

export default {
    getUser,
    handleLogin,
    logout,
    register,
    storeCampaign,
    tags,
    changePassword,
    updateProfile,
    showMessage,
    receiveMessage,
    sendMessage,
    follow,
    updateHeaderImage,
    updateAvatar,
    uploadImages
}
