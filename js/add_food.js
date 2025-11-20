// Placeholder for future interactivity like image preview
const imageInput = document.getElementById('image');
imageInput.addEventListener('change', function(){
    if(this.files && this.files[0]){
        console.log(`Selected file: ${this.files[0].name}`);
    }
});
