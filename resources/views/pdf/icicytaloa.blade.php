<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ICICyTA 2024 Letter of Acceptance</title>
    <style>
        body {
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", Arial, sans-serif;
        background: #fff;
        color: #222;
        }

        .container {
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        }

        .header {
        background: #8d6cb3;
        color: #fff;
        padding: 2rem;
        text-align: left;
        }

        .header-title {
        font-size: 2.2rem;
        font-weight: bold;
        letter-spacing: 1px;
        margin-bottom: 6px;
        }

        .header-subtitle {
        font-size: 1.05rem;
        font-weight: 400;
        letter-spacing: 0.2px;
        }

        .header-subtitle .highlight {
        font-weight: bold;
        }

        .content {
        padding: 2rem;
        flex: 1;
        }

        .section-title {
        font-size: 1.1rem;
        font-weight: bold;
        margin-bottom: 18px;
        }

        .body-text {
        font-size: 1.05rem;
        margin-bottom: 18px;
        line-height: 1.7;
        text-align: justify;
        }

        .paper-title {
        font-size: 1.08rem;
        font-weight: bold;
        display: block;
        margin: 8px 0 4px 0;
        }

        .accepted {
        font-weight: bold;
        letter-spacing: 1px;
        }

        .date-location {
        margin-top: 18px;
        margin-bottom: 32px;
        font-size: 1.05rem;
        text-align: right;
        }

        .signature-block {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        margin-top: 16px;
        min-height: 90px;
        }

        .stamp {
        width: 110px;
        height: auto;
        margin-right: 18px;
        margin-bottom: 0;
        opacity: 0.85;
        }

        .signature-text {
        font-size: 1rem;
        line-height: 1.3;
        text-align: center;
        }

        .signature-name {
        font-weight: bold;
        text-decoration: underline;
        }

        .signature-title {
        font-size: 0.98rem;
        }

        .footer {
        background: #8d6cb3;
        padding: 2rem;
        text-align: center;
        margin-top: 40px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 36px;
        }

        .footer-logos {
        height: 36px;
        width: auto;
        }

        @media (max-width: 600px) {
        .container {
            max-width: 100vw;
        }
        .header,
        .content {
            padding: 18px 10px 10px 10px;
        }
        .footer {
            padding: 10px 0 6px 0;
        }
        }

    </style>
  </head>
  <body>
    <div class="container">
      <header class="header">
        <div class="header-title">ICICYTA 2024</div>
        <div class="header-subtitle">
          The 4<sup>TH</sup> International Conference on Intelligent Cybernetics
          Technology & Applications 2024 (<span class="highlight">ICICyTA</span
          >)
        </div>
      </header>
      <main class="content">
        <div class="section-title"><strong>LETTER OF ACCEPTANCE</strong></div>
        <div class="body-text">
          The 4<sup>th</sup> International Conference on Intelligent Cybernetics
          Technology & Applications 2024 (ICICyTA)<br /><br />
          Dear {{ implode(', ', $loa->author_names ?? []) }}
        </div>
        <div class="body-text">
          Organizing & Program Committee is pleased to announce that your paper
          :<br />
          <span class="paper-title" style="text-transform: uppercase"
            ><strong
              >({{ $loa->paper_id }}): {{ $loa->paper_title }}</strong
            ></span
          ><br />
          Was <span class="accepted" style="text-transform: uppercase">{{ $loa->status }}</span>
        </div>
        <div class="body-text">
          For The 4<sup>th</sup> International Conference on Intelligent
          Cybernetics Technology & Applications 2024 (ICICyTA). For finishing
          your registration please follow the instruction, which has been
          already send by e-mail to all authors of accepted papers.
        </div>
        <div class="body-text">
          The 4<sup>th</sup> International Conference on Intelligent Cybernetics
          Technology & Applications 2024 (ICICyTA 2024) with theme "From Data to
          Decisions: Cybernetics and Intelligent Systems in Healthcare, IoT, and
          Business" will be held on December 17-19, 2024 at Bali Indonesia.
        </div>
        <div class="date-location">{{ $loa->tempat_tanggal }}</div>
        <div class="signature-block">
          <img src="icicyta-stamp.png" alt="ICICyTA Stamp" class="stamp" />
          {{ $loa->signature->picture }}
          <div class="signature-text">
            <span class="signature-name"
              >{{ $loa->signature->nama_penandatangan }}</span
            ><br />
            <span class="signature-title">{{ $loa->signature->jabatan_penandatangan }}</span>
          </div>
        </div>
      </main>
      <footer class="footer">
        <img
          class="footer-logos"
          src="https://rb.gy/duo9um"
          alt="Telkom University"
        />
        <img class="footer-logos" src="https://rb.gy/k8c62g" alt="UTM" />
        <img class="footer-logos" src="https://rb.gy/c6tagb" alt="IEEE" />
      </footer>
    </div>
  </body>
</html>
