<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title>Balasan dari {{ $appName }}</title>
	</head>
	<body style="font-family:Arial,Helvetica,sans-serif;background:#f6f8fb;margin:0;padding:20px;color:#111">
		<div style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e6edf3;border-radius:8px;overflow:hidden">
			<div style="padding:20px;border-bottom:1px solid #eef3f7;background:#fafcff">
				<h2 style="margin:0;font-size:18px;color:#0b2540">Balasan dari {{ $appName }}</h2>
			</div>
			<div style="padding:20px;line-height:1.6;color:#213547">
				<p>Halo {{ $contactMessage->name }},</p>
				<p>Terima kasih sudah menghubungi kami. Berikut balasan dari tim admin:</p>

				<div style="background:#f4f7fb;border:1px solid #e6edf3;padding:12px;border-radius:6px;margin:16px 0;color:#0b2540">
					{!! nl2br(e($contactMessage->admin_reply ?? '')) !!}
				</div>

				<p><strong>Subjek pesan Anda:</strong> {{ $contactMessage->subject }}</p>

				<p>Jika masih ada kendala, Anda dapat membalas email ini atau mengirim pesan baru melalui halaman Hubungi Kami.</p>

				<p>Salam,<br>{{ $appName }}</p>
			</div>
			<div style="padding:12px 20px;background:#f8fbff;border-top:1px solid #eef3f7;color:#64727a;font-size:12px">
				<div>Ini adalah email otomatis — harap jangan membalas ke alamat ini kecuali diarahkan.</div>
			</div>
		</div>
	</body>
</html>