<?php
// Konfigurasi repo GitHub
$username = "gplusnation";
$repo = "Ruwayfi-Academy";
$folder = "Talkhis%20Ringkasan%20Sifat%20Sholat%20Nabi";

// URL API GitHub
$apiUrl = "https://api.github.com/repos/$username/$repo/contents/$folder";

// Ambil data dari GitHub API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'MyGithubAudioApp');
$response = curl_exec($ch);
curl_close($ch);

$files = json_decode($response, true);

// Filter hanya file mp3
$playlist = [];

if (is_array($files)) {
    foreach ($files as $file) {
        if (pathinfo($file['name'], PATHINFO_EXTENSION) === 'mp3') {
            $playlist[] = [
                'title' => pathinfo($file['name'], PATHINFO_FILENAME),
                'url' => $file['download_url']
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Full Playlist Talkhis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        h2 { text-align: center; }
        #playlist { max-width: 600px; margin: auto; }
        ul { list-style: none; padding: 0; }
        li { background: #fff; margin: 5px 0; padding: 10px; border-radius: 5px; cursor: pointer; }
        li:hover { background: #eaeaea; }
        .active { background: #d0eaff; }
        audio { width: 100%; margin-top: 20px; }
    </style>
</head>
<body>

<h2>Talkhis Ringkasan Sifat Sholat Nabi</h2>

<div id="playlist">
    <ul id="trackList">
        <?php foreach($playlist as $index => $track): ?>
            <li data-index="<?= $index ?>"><?= htmlspecialchars($track['title']) ?></li>
        <?php endforeach; ?>
    </ul>
    <audio id="audioPlayer" controls>
        <source id="audioSource" src="" type="audio/mpeg">
        Browser tidak mendukung audio.
    </audio>
</div>

<script>
    const playlist = <?= json_encode($playlist); ?>;
    const audioPlayer = document.getElementById('audioPlayer');
    const audioSource = document.getElementById('audioSource');
    const trackList = document.getElementById('trackList').children;

    // Fungsi untuk play lagu berdasarkan index
    function playTrack(index) {
        const track = playlist[index];
        audioSource.src = track.url;
        audioPlayer.load();
        audioPlayer.play();

        // Highlight track aktif
        for (let li of trackList) { li.classList.remove('active'); }
        trackList[index].classList.add('active');
    }

    // Tambahkan event listener ke tiap item
    for (let li of trackList) {
        li.addEventListener('click', function() {
            playTrack(this.dataset.index);
        });
    }

    // Auto play lagu pertama saat halaman dibuka
    if (playlist.length > 0) {
        playTrack(0);
    }
</script>

</body>
</html>
