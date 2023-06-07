<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;

// Đường dẫn đến file readme.txt
$inputFile = 'readme.txt';

// Đọc nội dung từ file
$text = file_get_contents($inputFile);

// Thiết lập thông tin xác thực
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/service_account_credentials.json');

// Tạo client Text-to-Speech
$client = new TextToSpeechClient();

// Tạo input text
$inputText = (new SynthesisInput())->setText($text);

// Thiết lập thông tin giọng nói
$voice = (new VoiceSelectionParams())
    ->setLanguageCode('en-US') // Ngôn ngữ và vùng miền của văn bản
    ->setSsmlGender(SsmlVoiceGender::FEMALE); // Giới tính giọng nói

// Thiết lập định dạng âm thanh đầu ra
$audioConfig = (new AudioConfig())
    ->setAudioEncoding(AudioEncoding::LINEAR16); // Sử dụng định dạng âm thanh WAV

// Gửi yêu cầu tạo âm thanh
$response = $client->synthesizeSpeech($inputText, $voice, $audioConfig);

// Lưu âm thanh thành file .wav
$outputFile = 'output.wav';
file_put_contents($outputFile, $response->getAudioContent());

echo "Đã tạo file âm thanh: $outputFile";

// Chạy file âm thanh: Bạn có thể phải tìm thư viện khác thay thế cho library dưới đây 
$audioData = file_get_contents($outputFile);
$audio = new \Salamcastro\Audio\AudioFile($audioData);
$audio->output();
