from fastapi import FastAPI, File, UploadFile, HTTPException, Form
from fastapi.middleware.cors import CORSMiddleware
import face_recognition
import numpy as np
import cv2

app = FastAPI(title="Face Recognition API for Attendance")

# ⚠️ EN PRODUCCIÓN: Reemplazar por consulta a DB (ej. MySQL o Redis)
# Simulación: {user_id: embedding_vector}
KNOWN_EMBEDDINGS = {
    # Ejemplo:
    # 1: np.array([...]),
    # 2: np.array([...]),
}
EVENT_USER_MAP = {
    # event_id → [user_id1, user_id2, ...]
    # 101: [1, 2, 3],
}

@app.post("/extract-embedding")
async def extract_embedding(file: UploadFile = File(...)):
    try:
        contents = await file.read()
        nparr = np.frombuffer(contents, np.uint8)
        img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
        if img is None:
            raise HTTPException(400, "Imagen no válida.")
        rgb_img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
        face_locations = face_recognition.face_locations(rgb_img, model="hog")
        if len(face_locations) != 1:
            raise HTTPException(400, "Debe haber exactamente 1 rostro en la imagen.")
        encodings = face_recognition.face_encodings(rgb_img, face_locations)
        return {"embedding": encodings[0].tolist()}
    except Exception as e:
        raise HTTPException(400, f"Error al procesar imagen: {str(e)}")

@app.post("/verify-face")
async def verify_face(event_id: int = Form(...), file: UploadFile = File(...)):
    # 🔑 Paso 1: Cargar embeddings de usuarios en el evento (simulado)
    user_ids_in_event = EVENT_USER_MAP.get(event_id, [])
    if not user_ids_in_event:
        raise HTTPException(404, f"No hay usuarios registrados para el evento {event_id}.")

    embeddings_to_check = {
        uid: KNOWN_EMBEDDINGS[uid]
        for uid in user_ids_in_event
        if uid in KNOWN_EMBEDDINGS
    }
    if not embeddings_to_check:
        raise HTTPException(404, "No hay embeddings disponibles para este evento.")

    # 🔑 Paso 2: Procesar imagen entrante
    contents = await file.read()
    nparr = np.frombuffer(contents, np.uint8)
    img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
    rgb_img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
    face_locations = face_recognition.face_locations(rgb_img, model="hog")
    if len(face_locations) == 0:
        raise HTTPException(400, "Ningún rostro detectado.")
    encoding = face_recognition.face_encodings(rgb_img, face_locations)[0]

    # 🔑 Paso 3: Comparar
    threshold = 0.6  # Configurable por evento en BD
    best_match_id = None
    best_distance = 1.0

    for user_id, known_embedding in embeddings_to_check.items():
        distance = face_recognition.face_distance([known_embedding], encoding)[0]
        if distance < best_distance:
            best_distance = distance
            best_match_id = user_id

    confidence = max(0.0, 1.0 - best_distance)
    match = confidence >= (1.0 - threshold)

    return {
        "match": match,
        "user_id": best_match_id if match else None,
        "confidence": round(float(confidence), 4),
        "threshold_used": threshold
    }

# ✅ Permitir peticiones desde Laravel (desarrollo)
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)