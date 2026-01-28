<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Minecraft 3D Skin Viewer Module</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jersey+20&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        body, html { 
            margin: 0; 
            padding: 0; 
            overflow: hidden; 
            height: 100%; 
            width: 100%; 
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: transparent;
            touch-action: none; /* Предотвращаем стандартные жесты браузера */
        }
        
        :root {
            --bg-color: #121212;
            --panel-bg: rgba(28, 28, 30, 0.9);
            --text-color: #ffffff;
            --text-secondary: #a1a1a6;
            --border-color: rgba(255, 255, 255, 0.15);
            --input-bg: #2c2c2e;
            --accent-color: #55ff55;
            --shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            --radius-lg: 20px;
            --radius-md: 12px;
            --window-border: 2px solid rgba(255, 255, 255, 0.2);
        }

        body.light-theme {
            --bg-color: #f2f2f7;
            --panel-bg: rgba(255, 255, 255, 0.9);
            --text-color: #1c1c1e;
            --text-secondary: #8e8e93;
            --border-color: rgba(0, 0, 0, 0.1);
            --input-bg: #ffffff;
            --accent-color: #00aa00;
            --shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            --window-border: 2px solid rgba(0, 0, 0, 0.15);
        }
        
        .skin-viewer-wrapper {
            position: relative;
            width: calc(100% - 40px);
            height: calc(100% - 40px);
            max-width: 1100px;
            max-height: 750px;
            background-color: var(--bg-color);
            overflow: hidden;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            transition: all 0.3s ease;
            border-radius: 32px; 
            border: var(--window-border);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #canvas-container {
            width: 100%;
            height: 100%;
            cursor: grab;
            display: block;
            border-radius: inherit;
        }

        #canvas-container:active {
            cursor: grabbing;
        }

        .ui-panel {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--panel-bg);
            padding: 12px 20px;
            border-radius: var(--radius-lg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: stretch;
            width: fit-content;
            min-width: 200px;
            max-width: 90%; 
            z-index: 10;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            color: var(--text-color);
        }

        .ui-panel.hidden {
            opacity: 0;
            visibility: hidden;
            transform: translateX(-50%) translateY(30px) scale(0.95);
            pointer-events: none;
        }

        .close-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(128, 128, 128, 0.1);
            border: none;
            color: var(--text-color);
            cursor: pointer;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .settings-toggle {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 44px;
            height: 44px;
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 5;
            box-shadow: var(--shadow);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            color: var(--text-color);
            opacity: 0;
            visibility: hidden;
            backdrop-filter: blur(8px);
        }

        .settings-toggle.visible {
            opacity: 1;
            visibility: visible;
        }

        @media (min-width: 480px) {
            .ui-panel {
                flex-direction: row;
                align-items: center;
                gap: 20px;
                padding: 16px 24px;
            }
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .control-group label.group-title {
            font-size: 10px;
            text-transform: uppercase;
            color: var(--text-secondary);
            font-weight: 700;
            letter-spacing: 1px;
        }

        select {
            background: var(--input-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
            padding: 8px 12px;
            border-radius: var(--radius-md);
            font-size: 13px;
            outline: none;
            cursor: pointer;
            width: 140px;
        }
        
        select option {
            background-color: #1c1c1e;
            color: #ffffff;
        }

        .checkbox-container {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--text-color);
            cursor: pointer;
        }
        
        .checkbox-wrapper input {
            width: 14px;
            height: 14px;
            accent-color: var(--accent-color);
        }
    </style>
</head>
<body class="dark-theme">

    <div class="skin-viewer-wrapper">
        <div id="canvas-container"></div>

        <div id="openSettings" class="settings-toggle" onclick="toggleSettings(true)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33-1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        </div>

        <div id="uiPanel" class="ui-panel">
            <button id="closeBtn" class="close-btn" onclick="toggleSettings(false)" title="Скрыть">&times;</button>
            
            <div class="control-group">
                <label class="group-title">Модель</label>
                <select id="modelType" onchange="updateModelType()">
                    <option value="steve">Steve</option>
                    <option value="alex">Alex</option>
                </select>
            </div>

            <div class="control-group">
                <label class="group-title">Вид</label>
                <div class="checkbox-container">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" id="walkAnim" checked> Ходьба
                    </label>
                    <label class="checkbox-wrapper">
                        <input type="checkbox" id="showOverlay" checked> Слои
                    </label>
                </div>
            </div>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        
        const SETTINGS = {
            skin: urlParams.get('skin') || 'https://minotar.net/skin/char',
            cloak: urlParams.get('cloak') || '',
            username: urlParams.get('name') || 'Steve',
            autoRotate: urlParams.get('autoRotate') !== 'false',
            walk: urlParams.get('walk') !== 'false',
            showUi: urlParams.get('showUi') !== 'false',
            allowToggle: urlParams.get('allowToggle') !== 'false',
            theme: urlParams.get('theme') || 'dark'
        };

        let scene, camera, renderer, skinGroup, nameTagMesh, cloakMesh;
        let parts = {};
        let isDragging = false;
        let previousMousePosition = { x: 0, y: 0 };
        let rotation = { x: 0.1, y: 0.5 };
        let zoom = 45;
        
        let lastInteractionTime = 0; 
        const AUTO_ROTATE_DELAY = 5000; 
        const AUTO_ROTATE_SPEED = 0.01; 

        // Переменные для тача
        let lastTouchDistance = 0;

        const clock = new THREE.Clock();
        const textureLoader = new THREE.TextureLoader();

        const SKIN_DATA = {
            head:     { base: [0, 0],   overlay: [32, 0],  size: [8, 8, 8] },
            body:     { base: [16, 16], overlay: [16, 32], size: [8, 12, 4] },
            rightArm: { base: [40, 16], overlay: [40, 32], size: [4, 12, 4] },
            leftArm:  { base: [32, 48], overlay: [48, 48], size: [4, 12, 4] },
            rightLeg: { base: [0, 16],  overlay: [0, 32],  size: [4, 12, 4] },
            leftLeg:  { base: [16, 48], overlay: [0, 48],  size: [4, 12, 4] }
        };

        function toggleSettings(show) {
            if (!SETTINGS.allowToggle && !show) return;
            const panel = document.getElementById('uiPanel');
            const toggle = document.getElementById('openSettings');
            if (show) {
                panel.classList.remove('hidden');
                toggle.classList.remove('visible');
            } else {
                panel.classList.add('hidden');
                toggle.classList.add('visible');
            }
        }

        function applyUrlSettings() {
            if (SETTINGS.theme === 'light') {
                document.body.classList.remove('dark-theme');
                document.body.classList.add('light-theme');
            }

            const panel = document.getElementById('uiPanel');
            const toggle = document.getElementById('openSettings');
            const closeBtn = document.getElementById('closeBtn');
            const walkCheck = document.getElementById('walkAnim');

            if (!SETTINGS.showUi) {
                panel.style.display = 'none';
                toggle.style.display = 'none';
            }
            
            if (!SETTINGS.allowToggle) {
                closeBtn.style.display = 'none';
            }

            walkCheck.checked = SETTINGS.walk;
        }

        function init() {
            applyUrlSettings();

            const container = document.getElementById('canvas-container');
            const width = container.clientWidth;
            const height = container.clientHeight;

            scene = new THREE.Scene();
            camera = new THREE.PerspectiveCamera(55, width / height, 0.1, 1000);
            camera.position.set(0, 5, zoom);

            renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            renderer.setSize(width, height);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            container.appendChild(renderer.domElement);

            const ambientLightIntensity = SETTINGS.theme === 'light' ? 1.0 : 0.9;
            const directionalLightIntensity = SETTINGS.theme === 'light' ? 0.4 : 0.3;

            scene.add(new THREE.AmbientLight(0xffffff, ambientLightIntensity));
            const sun = new THREE.DirectionalLight(0xffffff, directionalLightIntensity);
            sun.position.set(10, 20, 15);
            scene.add(sun);

            skinGroup = new THREE.Group();
            skinGroup.position.y = -2;
            scene.add(skinGroup);

            createCharacter();
            
            document.fonts.load('1em "Jersey 20"').then(() => {
                updateNameTag();
            });

            setupControls();
            animate();
            
            window.addEventListener('resize', onResize);
            const resizeObserver = new ResizeObserver(() => onResize());
            resizeObserver.observe(container);
        }

        function createNameTagTexture(name) {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const fontSize = 80; 
            ctx.font = `700 ${fontSize}px "Jersey 20", cursive`;
            const textWidth = ctx.measureText(name).width;
            const paddingX = 32;
            const paddingY = 16;
            canvas.width = textWidth + paddingX * 2;
            canvas.height = fontSize + paddingY * 2;
            ctx.imageSmoothingEnabled = false;
            
            ctx.fillStyle = SETTINGS.theme === 'light' ? 'rgba(0, 0, 0, 0.4)' : 'rgba(0, 0, 0, 0.5)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            ctx.font = `700 ${fontSize}px "Jersey 20", cursive`;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            
            ctx.fillStyle = '#3f3f3f';
            ctx.fillText(name, (canvas.width / 2) + 4, (canvas.height / 2) + 4);
            
            ctx.fillStyle = '#ffffff';
            ctx.fillText(name, canvas.width / 2, canvas.height / 2);
            
            const texture = new THREE.CanvasTexture(canvas);
            texture.magFilter = THREE.NearestFilter;
            texture.minFilter = THREE.NearestFilter;
            return texture;
        }

        async function updateNameTag() {
            const name = SETTINGS.username || '';
            await document.fonts.load('1em "Jersey 20"');
            if (nameTagMesh) {
                skinGroup.remove(nameTagMesh);
                nameTagMesh.geometry.dispose();
                if (nameTagMesh.material.map) nameTagMesh.material.map.dispose();
                nameTagMesh.material.dispose();
            }
            if (!name) return;
            const texture = createNameTagTexture(name);
            const aspect = texture.image.width / texture.image.height;
            const h = 4.2; 
            const w = h * aspect;
            const geometry = new THREE.PlaneGeometry(w, h);
            const material = new THREE.MeshBasicMaterial({ map: texture, transparent: true, side: THREE.DoubleSide });
            nameTagMesh = new THREE.Mesh(geometry, material);
            
            nameTagMesh.position.y = 26.5; 
            skinGroup.add(nameTagMesh);
        }

        function createUVGeometry(w, h, d, u, v, tw = 64, th = 64, expand = 0) {
            const geo = new THREE.BoxGeometry(w + expand, h + expand, d + expand);
            const uvAttr = geo.attributes.uv;
            
            const faces = [
                { x: u + d + w, y: v + d, width: d, height: h }, // right
                { x: u,         y: v + d, width: d, height: h }, // left
                { x: u + d,     y: v,     width: w, height: d }, // top
                { x: u + d + w, y: v,     width: w, height: d }, // bottom
                { x: u + d,     y: v + d, width: w, height: h }, // front
                { x: u + d + w + d, y: v + d, width: w, height: h } // back
            ];

            for (let i = 0; i < 6; i++) {
                const f = faces[i];
                const idx = i * 4;
                
                const u1 = f.x / tw; 
                const v1 = 1 - (f.y + f.height) / th;
                const u2 = (f.x + f.width) / tw; 
                const v2 = 1 - f.y / th;

                uvAttr.setXY(idx,     u1, v2);
                uvAttr.setXY(idx + 1, u2, v2);
                uvAttr.setXY(idx + 2, u1, v1);
                uvAttr.setXY(idx + 3, u2, v1);
            }
            return geo;
        }

        function createCharacter() {
            const isAlex = document.getElementById('modelType').value === 'alex';
            const skinMat = new THREE.MeshStandardMaterial({ transparent: true, alphaTest: 0.5 });
            const overlayMat = new THREE.MeshStandardMaterial({ transparent: true, side: THREE.DoubleSide, alphaTest: 0.01 });

            Object.values(parts).forEach(p => {
                p.traverse(child => {
                    if (child.isMesh) {
                        child.geometry.dispose();
                        child.material.dispose();
                    }
                });
                skinGroup.remove(p);
            });
            if (cloakMesh) {
                cloakMesh.geometry.dispose();
                cloakMesh.material.dispose();
                skinGroup.remove(cloakMesh);
            }

            for (const [key, data] of Object.entries(SKIN_DATA)) {
                const group = new THREE.Group();
                let [w, h, d] = data.size;
                if (isAlex && (key === 'rightArm' || key === 'leftArm')) w = 3;
                
                const base = new THREE.Mesh(createUVGeometry(w, h, d, data.base[0], data.base[1]), skinMat.clone());
                const overlay = new THREE.Mesh(createUVGeometry(w, h, d, data.overlay[0], data.overlay[1], 64, 64, 0.5), overlayMat.clone());
                overlay.name = "overlay";
                
                group.add(base); 
                group.add(overlay);

                if (key === 'head') {
                    base.geometry.translate(0, h/2, 0);
                    overlay.geometry.translate(0, h/2, 0);
                } else if (key !== 'body') {
                    base.geometry.translate(0, -h/2, 0);
                    overlay.geometry.translate(0, -h/2, 0);
                }
                
                parts[key] = group;
                skinGroup.add(group);
            }

            const cw = 10, ch = 16, cd = 1;
            const cloakGeo = new THREE.BoxGeometry(cw, ch, cd);
            const uvAttr = cloakGeo.attributes.uv;
            const tw = 64, th = 32;

            const cloakFaces = [
                { x: 0,  y: 1,  w: 1,  h: 16 }, // 0: Right
                { x: 11, y: 1,  w: 1,  h: 16 }, // 1: Left
                { x: 1,  y: 0,  w: 10, h: 1 },  // 2: Top
                { x: 11, y: 0,  w: 10, h: 1 },  // 3: Bottom
                { x: 12, y: 1,  w: 10, h: 16 }, // 4: Front
                { x: 1,  y: 1,  w: 10, h: 16 }  // 5: Back
            ];

            for (let i = 0; i < 6; i++) {
                const f = cloakFaces[i];
                const idx = i * 4;
                let u1 = f.x / tw;
                let u2 = (f.x + f.w) / tw;
                let v1 = 1 - (f.y + f.h) / th;
                let v2 = 1 - f.y / th;
                [u1, u2] = [u2, u1];
                uvAttr.setXY(idx,     u1, v2);
                uvAttr.setXY(idx + 1, u2, v2);
                uvAttr.setXY(idx + 2, u1, v1);
                uvAttr.setXY(idx + 3, u2, v1);
            }

            cloakMesh = new THREE.Mesh(cloakGeo, new THREE.MeshStandardMaterial({ 
                transparent: true, 
                alphaTest: 0.5, 
                side: THREE.DoubleSide 
            }));
            
            cloakMesh.geometry.translate(0, -8, -0.5); 
            cloakMesh.position.set(0, 14, -2.1); 
            cloakMesh.rotation.x = 0.05;
            skinGroup.add(cloakMesh);

            parts.head.position.y = 14; 
            parts.body.position.y = 8;
            const armOffset = isAlex ? 5.5 : 6;
            parts.rightArm.position.set(-armOffset, 14, 0);
            parts.leftArm.position.set(armOffset, 14, 0);
            parts.rightLeg.position.set(-2, 2, 0);
            parts.leftLeg.position.set(2, 2, 0);

            loadResources(SETTINGS.skin, SETTINGS.cloak);
        }

        function loadResources(skinUrl, cloakUrl) {
            textureLoader.load(skinUrl, (tex) => {
                tex.magFilter = THREE.NearestFilter;
                tex.minFilter = THREE.NearestFilter;
                skinGroup.traverse(child => {
                    if (child.isMesh && child !== nameTagMesh && child !== cloakMesh) { 
                        child.material.map = tex; 
                        child.material.needsUpdate = true; 
                    }
                });
            });

            if (cloakUrl) {
                textureLoader.load(cloakUrl, (tex) => {
                    tex.magFilter = THREE.NearestFilter;
                    tex.minFilter = THREE.NearestFilter;
                    cloakMesh.material.map = tex;
                    cloakMesh.material.needsUpdate = true;
                    cloakMesh.visible = true;
                }, undefined, () => {
                    cloakMesh.visible = false;
                });
            } else {
                cloakMesh.visible = false;
            }
        }

        function updateModelType() { createCharacter(); if(nameTagMesh) updateNameTag(); }

        function animate() {
            requestAnimationFrame(animate);
            const time = clock.getElapsedTime() * 4;
            const walking = document.getElementById('walkAnim').checked;
            const overlayOn = document.getElementById('showOverlay').checked;
            const now = Date.now();

            if (SETTINGS.autoRotate && !isDragging && (now - lastInteractionTime > AUTO_ROTATE_DELAY || lastInteractionTime === 0)) {
                rotation.y += AUTO_ROTATE_SPEED;
                skinGroup.rotation.y = rotation.y;
            }

            if (nameTagMesh) nameTagMesh.lookAt(camera.position);

            skinGroup.traverse(child => { if (child.name === "overlay") child.visible = overlayOn; });

            if (walking) {
                const wave = Math.sin(time);
                parts.rightArm.rotation.x = -wave * 0.6;
                parts.leftArm.rotation.x = wave * 0.6;
                parts.rightLeg.rotation.x = wave * 0.6;
                parts.leftLeg.rotation.x = -wave * 0.6;
                parts.head.rotation.y = Math.sin(time * 0.5) * 0.1;
                cloakMesh.rotation.x = 0.15 + Math.abs(wave * 0.4);
            } else {
                cloakMesh.rotation.x = THREE.MathUtils.lerp(cloakMesh.rotation.x, 0.05, 0.1);
                parts.rightArm.rotation.x = 0;
                parts.leftArm.rotation.x = 0;
                parts.rightLeg.rotation.x = 0;
                parts.leftLeg.rotation.x = 0;
                parts.head.rotation.y = 0;
            }

            renderer.render(scene, camera);
        }

        // Вспомогательная функция для расчета расстояния между двумя пальцами
        function getTouchDistance(touches) {
            return Math.hypot(
                touches[0].pageX - touches[1].pageX,
                touches[0].pageY - touches[1].pageY
            );
        }

        function handleStart(x, y) {
            isDragging = true;
            lastInteractionTime = Date.now();
            previousMousePosition = { x, y };
        }

        function handleMove(x, y) {
            if (!isDragging) return;
            lastInteractionTime = Date.now();
            const delta = { x: x - previousMousePosition.x, y: y - previousMousePosition.y };
            
            rotation.y += delta.x * 0.01;
            rotation.x += delta.y * 0.01;
            rotation.x = Math.max(-Math.PI/2.5, Math.min(Math.PI/2.5, rotation.x));
            
            skinGroup.rotation.y = rotation.y;
            skinGroup.rotation.x = rotation.x;
            previousMousePosition = { x, y };
        }

        function handleZoom(delta) {
            lastInteractionTime = Date.now();
            zoom += delta;
            zoom = Math.max(15, Math.min(150, zoom));
            camera.position.z = zoom;
        }

        function setupControls() {
            const container = document.getElementById('canvas-container');

            // МЫШЬ
            container.addEventListener('mousedown', e => handleStart(e.clientX, e.clientY));
            window.addEventListener('mouseup', () => { isDragging = false; lastInteractionTime = Date.now(); });
            window.addEventListener('mousemove', e => handleMove(e.clientX, e.clientY));
            container.addEventListener('wheel', e => {
                handleZoom(e.deltaY * 0.04);
                e.preventDefault();
            }, { passive: false });

            // ТАЧ (СЕНСОР)
            container.addEventListener('touchstart', e => {
                if (e.touches.length === 1) {
                    handleStart(e.touches[0].pageX, e.touches[0].pageY);
                } else if (e.touches.length === 2) {
                    isDragging = false; // Отключаем вращение при зуме
                    lastTouchDistance = getTouchDistance(e.touches);
                }
            }, { passive: false });

            container.addEventListener('touchmove', e => {
                if (e.touches.length === 1) {
                    handleMove(e.touches[0].pageX, e.touches[0].pageY);
                } else if (e.touches.length === 2) {
                    const currentDist = getTouchDistance(e.touches);
                    const diff = lastTouchDistance - currentDist;
                    handleZoom(diff * 0.2);
                    lastTouchDistance = currentDist;
                }
                e.preventDefault(); // Предотвращаем скролл страницы
            }, { passive: false });

            container.addEventListener('touchend', () => {
                isDragging = false;
                lastInteractionTime = Date.now();
            });
        }

        function onResize() {
            const container = document.getElementById('canvas-container');
            if (!container) return;
            const width = container.clientWidth;
            const height = container.clientHeight;
            camera.aspect = width / height;
            camera.updateProjectionMatrix();
            renderer.setSize(width, height, false);
            if (height < 400) {
                camera.position.y = 8;
                skinGroup.scale.set(0.8, 0.8, 0.8);
            } else {
                camera.position.y = 5;
                skinGroup.scale.set(1, 1, 1);
            }
        }

        window.onload = init;
    </script>
</body>
</html>