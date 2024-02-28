<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\EventRequest;

class EventController extends Controller
{
    public function create(EventRequest $request)
    {
        try {
            $validate = $request->validated();
            $event = Event::create($validate);
            return response()->json($event, JsonResponse::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al procesar la solicitud.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update($id, EventRequest $request)
    {
        try{
            $event = Event::find($id);
            if(!$event){
                return response()->json(['message'=>'No se ha encontrado ningún evento para editar'],JsonResponse::HTTP_NOT_FOUND);
            }
            $event->name = $request->name;
            $event->description = $request->description;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->update();

            return response()->json($event, JsonResponse::HTTP_OK);
        }catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al procesar la solicitud.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'No se ha encontrado ningún evento para eliminar'], JsonResponse::HTTP_NOT_FOUND);
        }
        $event->delete();
        return response()->json(['message' => 'Evento eliminado con éxito'], JsonResponse::HTTP_OK);
    }
}
