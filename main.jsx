
import { useEffect, useState } from "react";
import { Card, CardContent } from "@/components/ui/card";

export default function Home() {
  const [videos, setVideos] = useState([]);

  useEffect(() => {
    fetch("/videos.json")
      .then((res) => res.json())
      .then((data) => setVideos(data));
  }, []);

  return (
    <main className="p-4 md:p-8 max-w-5xl mx-auto">
      <h1 className="text-3xl font-bold mb-6 text-center">قناة أبواب الذهب</h1>
      <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {videos.map((video, index) => (
          <Card key={index} className="rounded-2xl shadow-md">
            <video
              controls
              src={video.url}
              className="w-full h-48 object-cover rounded-t-2xl"
            ></video>
            <CardContent>
              <p className="text-center mt-2 font-medium">{video.title || `فيديو ${index + 1}`}</p>
            </CardContent>
          </Card>
        ))}
      </div>
    </main>
  );
}
