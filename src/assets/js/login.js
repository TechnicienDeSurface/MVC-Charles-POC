function createParticle() {
    const particle = document.createElement("div");
    particle.className = "absolute w-1 h-1 bg-white rounded-full opacity-30";
    particle.style.left = Math.random() * 100 + "%";
    particle.style.top = "100%";
    particle.style.animation = `float ${
        3 + Math.random() * 4
    }s linear infinite`;
    document.body.appendChild(particle);

    setTimeout(() => {
        particle.remove();
    }, 7000);
}

setInterval(createParticle, 500);
