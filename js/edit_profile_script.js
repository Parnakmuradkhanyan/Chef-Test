class ProfileEditModel {
    constructor() {}
}

class ProfileEditView {

    constructor() {
        this.userPhoto = document.getElementById('userPhoto');
        this.previewImage = document.getElementById('profile-image-preview');
        this.userCoverPhoto = document.getElementById('userCoverPhoto');
        this.previewImageCover = document.getElementById('profile-cover-image-preview');
    }

    clearInput() {
        this.userPhoto.value = '';
        this.userCoverPhoto.value = '';
    }

    displayImage(src) {
        this.previewImage.src = src;
    }

    displayCoverImage(src) {
        this.previewImageCover.src = src;
    }

}

class ProfileEditController {
    
    constructor(model, view) {
        this.model = model;
        this.view = view;

        this.view.userCoverPhoto.addEventListener('change', this.handleCoverPhotoChange.bind(this));
        this.view.userPhoto.addEventListener('change', this.handlePhotoChange.bind(this));
    }

    handlePhotoChange(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.view.displayImage(e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    handleCoverPhotoChange(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.view.displayCoverImage(e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
}

const model = new ProfileEditModel();
const view = new ProfileEditView();
const controller = new ProfileEditController(model, view);
