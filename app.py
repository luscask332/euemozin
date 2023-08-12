from flask import Flask, render_template, request, redirect, url_for
import os
from werkzeug.utils import secure_filename

app = Flask(__name__)

UPLOAD_FOLDER = 'uploads'
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg', 'gif'}

app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

memories = []

def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

@app.route('/')
def index():
    return render_template('index.html', memories=memories)

@app.route('/memory/<int:memory_id>')
def view_memory(memory_id):
    if memory_id < len(memories):
        memory = memories[memory_id]
        return render_template('memory.html', memory=memory, memory_id=memory_id)
    return "Memory not found."

@app.route('/add_memory', methods=['POST'])
def add_memory():
    title = request.form.get('title')
    content = request.form.get('content')
    if 'photo' not in request.files:
        return redirect(request.url)
    photo = request.files['photo']
    if photo.filename == '':
        return redirect(request.url)
    if photo and allowed_file(photo.filename):
        filename = secure_filename(photo.filename)
        photo_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
        photo.save(photo_path)
        memory = {'title': title, 'content': content, 'photo': photo_path}
        memories.append(memory)
    return redirect(url_for('index'))

@app.route('/delete_memory/<int:memory_id>', methods=['POST'])
def delete_memory(memory_id):
    if memory_id < len(memories):
        memory = memories.pop(memory_id)
        if os.path.exists(memory['photo']):
            os.remove(memory['photo'])
    return redirect(url_for('index'))

if __name__ == '__main__':
    if not os.path.exists(UPLOAD_FOLDER):
        os.makedirs(UPLOAD_FOLDER)
    app.run(debug=True)
