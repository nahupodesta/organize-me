<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    public function create(EventRequest $request)
    {
        try {
            $userId = Auth::id();
            $isValidate = $request->validated();
            $isValidate['user_id'] = $userId;

            $eventCreated = Event::create($isValidate);
            $responseEvent = [
                'id' => $eventCreated->id,
                'name' => $eventCreated->name,
                'description' => $eventCreated->description,
                'start_date' => $eventCreated->start_date,
                'end_date' => $eventCreated->end_date,
            ];
            return response()->json($responseEvent, JsonResponse::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            dd($e->getMessage());
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

            $responseEvent = [
                'id' => $event->id,
                'name' => $event->name,
                'description' => $event->description,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
            ];

            return response()->json($responseEvent, JsonResponse::HTTP_OK);
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

    public function eventsByUser($userId)
    {
        $events = $this->getEventsByUsers($userId);
        return $events;
    }

    private function getEventsByUsers($userId)
    {
        $user = User::with('events')->find($userId);
        $events = $user->events;
        return $events;
    }


}
