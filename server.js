const express = require('express');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');
const cors = require('cors');

const app = express();
const port = 3000;

// Middleware
app.use(bodyParser.json());
app.use(cors());

// Koneksi ke MongoDB
mongoose.connect('mongodb://localhost:27017/undangan', { useNewUrlParser: true, useUnifiedTopology: true })
  .then(() => console.log('Terhubung ke MongoDB'))
  .catch(err => console.error('Koneksi ke MongoDB gagal', err));

// Definisi Skema dan Model
const messageSchema = new mongoose.Schema({
  name: String,
  message: String
});

const Message = mongoose.model('Message', messageSchema);

// Endpoint untuk mendapatkan semua ucapan
app.get('/messages', async (req, res) => {
  const messages = await Message.find();
  res.json(messages);
});

// Endpoint untuk menambahkan ucapan baru
app.post('/messages', async (req, res) => {
  const newMessage = new Message({
    name: req.body.name,
    message: req.body.message
  });

  await newMessage.save();
  res.status(201).json(newMessage);
});

// Jalankan server
app.listen(port, () => {
  console.log(`Server berjalan di http://localhost:${port}`);
});