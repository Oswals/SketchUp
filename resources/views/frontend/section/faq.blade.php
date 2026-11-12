{{-- resources/views/frontend/section/faq.blade.php --}}

<style>
    /* Content Section - Style jpg2png épuré */
    #content-section {
        background: #ffffff;
        padding: 80px 0;
    }

    /* Header centré conservé */
    .content-header {
        text-align: center;
        max-width: 700px;
        margin: 0 auto 4rem;
    }

    .content-header h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .content-header .subtitle {
        font-size: 1.1rem;
        color: #6c757d;
    }

    /* Container simple style jpg2png */
    .content-wrapper {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Typography simple et épurée */
    .content-wrapper h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #3150BF;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
    }

    .content-wrapper p {
        font-size: 1.05rem;
        color: #555;
        line-height: 1.8;
        margin-bottom: 1.5rem;
    }

    .content-wrapper strong {
        color: #3150BF;
        font-weight: 600;
    }

    .content-wrapper em {
        font-style: italic;
        color: #666;
    }

    /* Responsive */
    @media (max-width: 768px) {
        #content-section {
            padding: 60px 0;
        }

        .content-header h2 {
            font-size: 1.6rem;
        }

        .content-header .subtitle {
            font-size: 1rem;
        }

        .content-wrapper h3 {
            font-size: 1.3rem;
            margin-top: 2rem;
        }

        .content-wrapper p {
            font-size: 1rem;
        }
    }
</style>

<!-- Content Section -->
<section id="content-section">

    <div class="content-header">
        <h2>Frequently Asked Questions</h2>
        <p class="subtitle">Everything you need to know about converting your SketchUp files</p>
    </div>

    <div class="content-wrapper">

        <h3>Can I convert SketchUp 2026 to SketchUp 2017?</h3>

        <p>
            Yes. You can downconvert any newer SketchUp file to any older version we support — including going from <strong>SketchUp 2026 all the way back to SketchUp 2017</strong>. Simply upload your file and select the target version. The tool handles the rest automatically, with no SketchUp installation required.
        </p>

        <h3>Are textures and materials preserved?</h3>

        <p>
            Yes. Textures and materials are preserved whenever the target version supports them. <strong>Geometry, groups, components, scenes, and camera views</strong> are also kept intact. If a material or feature introduced in a newer version is not available in the older format, SketchUp automatically adapts it to the closest compatible equivalent. This is a technical limitation of SketchUp itself, not of our conversion tool.
        </p>

        <h3>How long does conversion take?</h3>

        <p>
            Most conversions complete in <strong>under 60 seconds</strong>. Processing time depends on the size and complexity of your SketchUp file, but even large architectural models are handled quickly. Everything runs on our servers — no waiting on your machine, no plugins, no setup.
        </p>

        <h3>Is my file deleted after conversion?</h3>

        <p>
            Yes. Your uploaded and converted files are stored securely and <strong>automatically deleted after 7 days</strong>. During that window, you can download your converted file as many times as needed. Processing is fully automated — no one has manual access to your data, and your files are never shared with third parties.
        </p>

        <h3>Does it work on Mac?</h3>

        <p>
            Yes. DownConvert SketchUp Files works on <strong>macOS, Windows, and Linux</strong>. It runs entirely in your browser — no installation, no plugins, no extensions required. All you need is a modern web browser and an internet connection.
        </p>

        <h3>Is SketchUp required to use this tool?</h3>

        <p>
            No. You do not need SketchUp installed, a SketchUp license, or a SketchUp subscription. This is particularly useful when you receive a file from a client but don't have the right version of SketchUp to open it. <strong>No upgrade required</strong> — our servers handle everything.
        </p>

        <h3>What file sizes are supported?</h3>

        <p>
            Files up to <strong>100 MB</strong> are supported. This covers the vast majority of SketchUp projects, including complex architectural models, detailed interior designs, and large urban planning files. If your file exceeds this limit, consider splitting the model into separate components before converting.
        </p>

    </div>

</section>