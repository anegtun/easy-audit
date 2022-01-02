const EasyAuditCompress = (function() {

    const compress = function(file, maxSize, quality, imageType) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                getImage(e.target.result).then(image => {
                    const { height, width } = calculateNewDims(image, maxSize);
                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(image, 0, 0, width, height);
                    resolve(canvas.toDataURL(imageType, quality));
                });
            }
            reader.readAsDataURL(file);
        });
    }

    const getImage = (dataUrl) => {
        return new Promise((resolve, reject) => {
            const image = new Image();
            image.src = dataUrl;
            image.onload = () => resolve(image);
            image.onerror = (el, err) => reject(err.error);
        });
    }

    const calculateNewDims = (image, maxSize) => {
        const oldW = image.naturalWidth;
        const oldH = image.naturalHeight;
        const largest = Math.max(oldW, oldH);
        const ratio = (maxSize > 0 && largest > maxSize) ? maxSize / largest : 1;
        return {
            height: Math.floor(oldH * ratio),
            width: Math.floor(oldW * ratio)
        };
    }

    return { compress };
})();