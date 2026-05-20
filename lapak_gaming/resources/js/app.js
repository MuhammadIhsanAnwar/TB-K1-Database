import './bootstrap';
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

const registerForm = document.querySelector('[data-register-form]');

if (registerForm) {
	const photoInput = registerForm.querySelector('[data-photo-input]');
	const previewImage = registerForm.querySelector('[data-photo-preview]');
	const photoError = registerForm.querySelector('[data-photo-error]');
	const cropModal = document.querySelector('[data-crop-modal]');
	const cropImage = document.querySelector('[data-crop-image]');
	const cropSaveButton = document.querySelector('[data-crop-save]');
	const cropCancelButton = document.querySelector('[data-crop-cancel]');
	const cropResetButton = document.querySelector('[data-crop-reset]');

	let cropper = null;
	let selectedFile = null;

	const hideModal = () => {
		cropModal.classList.add('hidden');
		cropModal.classList.remove('flex');
	};

	const showError = (message) => {
		if (!photoError) {
			return;
		}

		photoError.textContent = message;
		photoError.classList.remove('hidden');
	};

	const clearError = () => {
		if (!photoError) {
			return;
		}

		photoError.textContent = '';
		photoError.classList.add('hidden');
	};

	const setPreview = (source) => {
		previewImage.src = source;
	};

	const destroyCropper = () => {
		if (cropper) {
			cropper.destroy();
			cropper = null;
		}
	};

	const createCropper = () => {
		destroyCropper();

		cropper = new Cropper(cropImage, {
			aspectRatio: 1,
			viewMode: 1,
			dragMode: 'move',
			autoCropArea: 1,
			background: false,
			responsive: true,
			scalable: false,
			zoomable: true,
		});
	};

	const openCropper = (file) => {
		const reader = new FileReader();

		reader.onload = () => {
			cropImage.src = reader.result;
			cropModal.classList.remove('hidden');
			cropModal.classList.add('flex');
			createCropper();
		};

		reader.readAsDataURL(file);
	};

	photoInput.addEventListener('change', () => {
		clearError();

		const file = photoInput.files?.[0];

		if (!file) {
			selectedFile = null;
			return;
		}

		if (file.size > 5 * 1024 * 1024) {
			photoInput.value = '';
			selectedFile = null;
			showError('Ukuran foto tidak boleh lebih dari 5MB.');
			return;
		}

		if (!file.type.startsWith('image/')) {
			photoInput.value = '';
			selectedFile = null;
			showError('File harus berupa gambar.');
			return;
		}

		selectedFile = file;
		openCropper(file);
	});

	cropSaveButton.addEventListener('click', () => {
		if (!cropper || !selectedFile) {
			return;
		}

		cropper.getCroppedCanvas({
			width: 512,
			height: 512,
			imageSmoothingQuality: 'high',
		}).toBlob((blob) => {
			if (!blob) {
				showError('Gagal memproses gambar.');
				return;
			}

			const croppedFile = new File([blob], selectedFile.name, {
				type: 'image/jpeg',
				lastModified: Date.now(),
			});

			const dataTransfer = new DataTransfer();
			dataTransfer.items.add(croppedFile);
			photoInput.files = dataTransfer.files;

			setPreview(URL.createObjectURL(croppedFile));
			clearError();
			destroyCropper();
			hideModal();
		}, 'image/jpeg', 0.92);
	});

	const closeCropper = () => {
		selectedFile = null;
		photoInput.value = '';
		destroyCropper();
		hideModal();
	};

	cropCancelButton.addEventListener('click', closeCropper);
	cropResetButton.addEventListener('click', createCropper);
}
